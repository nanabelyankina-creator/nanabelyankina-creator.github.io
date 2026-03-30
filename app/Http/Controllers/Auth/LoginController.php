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

            if ($data['login_type'] === 'phone') {
                $user = User::where('phone', $data['identifier'])->first();
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
}