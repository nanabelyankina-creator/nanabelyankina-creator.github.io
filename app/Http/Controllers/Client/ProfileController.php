<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Rules\RussianPhone;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            abort(403, 'Доступ только для пациентов.');
        }

        return view('client.profile', compact('user', 'patient'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            abort(403, 'Доступ только для пациентов.');
        }

        $data = $request->validate([
            'last_name'   => ['sometimes', 'required', 'string', 'max:255'],
            'first_name'  => ['sometimes', 'required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'phone'       => [
                'sometimes',
                'required',
                'string',
                'max:20',
                new RussianPhone,
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'email'       => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'],
            'avatar_remove' => ['sometimes', 'boolean'],
        ]);

        $userData = [];
        $patientData = [];

        if (isset($data['last_name'])) $patientData['last_name'] = $data['last_name'];
        if (isset($data['first_name'])) $patientData['first_name'] = $data['first_name'];
        if (isset($data['middle_name'])) $patientData['middle_name'] = $data['middle_name'];
        if (array_key_exists('phone', $data)) $userData['phone'] = $data['phone'];
        if (array_key_exists('email', $data)) $userData['email'] = $data['email'];

        if (!empty($patientData)) {
            if (isset($patientData['last_name']) || isset($patientData['first_name'])) {
                $userData['name'] = ($patientData['first_name'] ?? $patient->first_name) . ' ' . ($patientData['last_name'] ?? $patient->last_name);
            }
            $patient->update($patientData);
        }

        // Аватар пользователя (для клиентов)
        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                $oldPath = str_replace('storage/', '', $user->avatar_path);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('avatar')->store('avatars/users', 'public');
            $userData['avatar_path'] = 'storage/' . $path;
        } elseif ($request->boolean('avatar_remove')) {
            if ($user->avatar_path) {
                $oldPath = str_replace('storage/', '', $user->avatar_path);
                Storage::disk('public')->delete($oldPath);
            }
            $userData['avatar_path'] = null;
        }

        if (!empty($userData)) {
            $user->update($userData);
        }

        return redirect()
            ->route('profile')
            ->with('success', 'Личные данные обновлены.');
    }

    public function showChangePasswordForm()
    {
        if (!Auth::user()->patient) {
            abort(403, 'Доступ только для пациентов.');
        }
        return view('client.change_password');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        if (!$user->patient) {
            abort(403, 'Доступ только для пациентов.');
        }

        $data = $request->validate([
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Текущий пароль указан неверно.']);
        }

        if (Hash::check($data['password'], $user->password)) {
            return back()
                ->withErrors(['password' => 'Новый пароль не должен совпадать с текущим.']);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        return redirect()
            ->route('profile')
            ->with('success', 'Пароль успешно изменён.');
    }
}