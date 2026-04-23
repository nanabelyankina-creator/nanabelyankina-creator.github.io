<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $loginAs = $request->input('login_as', 'client');

        if ($loginAs === 'staff') {
            $data = $request->validate([
                'email'    => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            $user = User::where('email', $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return back()
                    ->withErrors(['email' => 'Неверный email или пароль.'])
                    ->withInput($request->only('email', 'login_as'));
            }

            if (!$user->isDoctor() && !$user->isAdmin()) {
                return back()
                    ->withErrors(['email' => 'Вход по email предназначен для врачей и администраторов. Используйте вход «Клиент (пациент)».'])
                    ->withInput($request->only('email', 'login_as'));
            }
        } else {
            $data = $request->validate([
                'login_type' => ['required', 'in:phone,snils'],
                'identifier' => ['required', 'string'],
                'password'   => ['required', 'string'],
            ]);

            $user = null;
            $patient = null;

            if ($data['login_type'] === 'phone') {
                $user = $this->findPatientUserByPhone($data['identifier']);
            } else {
                $snils = \App\Services\SnilsValidator::normalize($data['identifier']);
                $patient = Patient::where('snils', $snils)->first();

                // Поддерживаем старые записи, где СНИЛС мог храниться с разделителями.
                if (!$patient) {
                    $snilsFormatted = \App\Services\SnilsValidator::format($snils);
                    $patient = Patient::where('snils', $snilsFormatted)->first();
                }

                if ($patient) {
                    $user = $patient->user;
                }
            }

            if ($data['login_type'] === 'snils' && isset($patient) && $patient && !$user) {
                return back()
                    ->withErrors(['identifier' => 'По этому СНИЛС запись найдена, но аккаунт не создан/не привязан. Войдите по телефону или обратитесь к администратору.'])
                    ->withInput($request->except('password'));
            }

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return back()
                    ->withErrors(['identifier' => 'Неверный логин или пароль.'])
                    ->withInput($request->except('password'));
            }
        }

        if ($user->is_blocked) {
            return redirect()->route('blocked');
        }

        Auth::login($user, $request->boolean('remember'));

        $redirect = $request->input('redirect') ?? $request->query('redirect');
        if ($redirect && filter_var($redirect, FILTER_VALIDATE_URL) && str_starts_with($redirect, url(''))) {
            return redirect($redirect);
        }

        if ($user->isDoctor()) {
            return redirect()->route('doctor.dashboard');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('profile');
    }

    private function findPatientUserByPhone(string $rawPhone): ?User
    {
        $digits = preg_replace('/\D/', '', $rawPhone);
        if (!$digits) {
            return null;
        }

        if (strlen($digits) === 11 && str_starts_with($digits, '8')) {
            $digits = '7' . substr($digits, 1);
        } elseif (strlen($digits) === 10 && str_starts_with($digits, '9')) {
            $digits = '7' . $digits;
        }

        if (strlen($digits) !== 11 || !str_starts_with($digits, '7')) {
            return null;
        }

        $plain = $digits;
        $plusPlain = '+' . $digits;
        $eightPlain = '8' . substr($digits, 1);
        $plusEightPlain = '+' . $eightPlain;
        $formatted = '+7 (' . substr($digits, 1, 3) . ') ' . substr($digits, 4, 3) . '-' . substr($digits, 7, 2) . '-' . substr($digits, 9, 2);
        $local = substr($digits, 1);

        $users = User::where('role', 'patient')
            ->whereNotNull('phone')
            ->where(function ($q) use ($plain, $plusPlain, $eightPlain, $plusEightPlain, $formatted, $local) {
                $q->where('phone', $plain)
                    ->orWhere('phone', $plusPlain)
                    ->orWhere('phone', $eightPlain)
                    ->orWhere('phone', $plusEightPlain)
                    ->orWhere('phone', $formatted)
                    ->orWhere('phone', $local);
            })
            ->get();

        foreach ($users as $candidate) {
            $candidateDigits = preg_replace('/\D/', '', (string) $candidate->phone);
            if (strlen($candidateDigits) === 10 && str_starts_with($candidateDigits, '9')) {
                $candidateDigits = '7' . $candidateDigits;
            } elseif (strlen($candidateDigits) === 11 && str_starts_with($candidateDigits, '8')) {
                $candidateDigits = '7' . substr($candidateDigits, 1);
            }

            if ($candidateDigits === $digits) {
                return $candidate;
            }
        }

        return null;
    }
}