<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor) {
            abort(403, 'Вы не являетесь врачом.');
        }

        return view('doctor.profile', compact('user', 'doctor'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor) {
            abort(403, 'Вы не являетесь врачом.');
        }

        $data = $request->validate([
            'last_name'   => ['required', 'string', 'max:255'],
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email'       => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'about'       => ['nullable', 'string'],
            'avatar'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'avatar_remove' => ['sometimes', 'boolean'],
        ]);

        $updateData = [
            'last_name'   => $data['last_name'],
            'first_name'  => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'about'       => $data['about'] ?? null,
        ];

        if ($request->hasFile('avatar')) {
            if ($doctor->avatar_path && str_starts_with($doctor->avatar_path, 'uploads/')) {
                @unlink(public_path($doctor->avatar_path));
            }

            File::ensureDirectoryExists(public_path('uploads/avatars/doctors'));

            $ext = $request->file('avatar')->getClientOriginalExtension() ?: 'jpg';
            $filename = 'doctor_' . $doctor->id . '_' . Str::random(8) . '.' . $ext;
            $request->file('avatar')->move(public_path('uploads/avatars/doctors'), $filename);

            $updateData['avatar_path'] = 'uploads/avatars/doctors/' . $filename;
        } elseif ($request->boolean('avatar_remove')) {
            if ($doctor->avatar_path && str_starts_with($doctor->avatar_path, 'uploads/')) {
                @unlink(public_path($doctor->avatar_path));
            }
            $updateData['avatar_path'] = null;
        }
        $doctor->update($updateData);

        $userUpdateData = [
            'name' => $data['first_name'].' '.$data['last_name'],
        ];

        if (array_key_exists('email', $data)) {
            $userUpdateData['email'] = $data['email'];
        }

        $user->update($userUpdateData);

        return redirect()
            ->route('doctor.profile')
            ->with('success', 'Профиль обновлён.');
    }

    public function showChangePasswordForm()
    {
        return view('doctor.change_password');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
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
            ->route('doctor.profile')
            ->with('success', 'Пароль успешно изменён.');
    }
}