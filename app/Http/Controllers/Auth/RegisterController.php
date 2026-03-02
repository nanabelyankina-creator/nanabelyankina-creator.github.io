<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Rules\RussianPhone;
use App\Rules\ValidSnils;
use App\Services\SnilsValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'last_name'   => ['required', 'string', 'max:255'],
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'snils'       => ['required', 'string', new ValidSnils],
            'phone'       => ['required', 'string', 'max:20', new RussianPhone, 'unique:users,phone'],
            'email'       => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'agree'       => ['accepted'],
        ], [
            'agree.accepted' => 'Для регистрации необходимо согласиться с обработкой персональных данных.',
        ]);

        $snils = SnilsValidator::normalize($data['snils']);

        $existingPatient = Patient::where('snils', $snils)->first();

        if ($existingPatient && $existingPatient->user_id) {
            return back()
                ->withErrors(['snils' => 'Пациент с таким СНИЛС уже зарегистрирован. Попробуйте войти или восстановить доступ.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::create([
            'name'       => $data['first_name'] . ' ' . $data['last_name'],
            'email'      => $data['email'] ?? null,
            'phone'      => $data['phone'],
            'password'   => Hash::make($data['password']),
            'role'       => 'patient',
            'is_blocked' => false,
        ]);

        if ($existingPatient) {
            $existingPatient->update([
                'user_id'     => $user->id,
                'last_name'   => $data['last_name'],
                'first_name'  => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
            ]);

            $patient = $existingPatient;
        } else {
            $patient = Patient::create([
                'user_id'     => $user->id,
                'last_name'   => $data['last_name'],
                'first_name'  => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'snils'       => $snils,
            ]);
        }

        Auth::login($user);

        return redirect()->route('profile')->with('success', 'Вы успешно зарегистрированы. Все ваши записи привязаны к кабинету.');
    }
}