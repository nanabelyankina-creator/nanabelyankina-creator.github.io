<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
            'about'       => ['nullable', 'string'],
            'avatar'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($doctor->avatar_path) {
                $oldPath = str_replace('storage/', '', $doctor->avatar_path);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('avatar')->store('avatars/doctors', 'public');
            $data['avatar_path'] = 'storage/' . $path;
        } elseif ($request->boolean('avatar_remove')) {
            if ($doctor->avatar_path) {
                $oldPath = str_replace('storage/', '', $doctor->avatar_path);
                Storage::disk('public')->delete($oldPath);
            }
            $data['avatar_path'] = null;
        }

        $updateData = [
            'last_name'   => $data['last_name'],
            'first_name'  => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'about'       => $data['about'] ?? null,
        ];
        if (isset($data['avatar_path'])) {
            $updateData['avatar_path'] = $data['avatar_path'];
        }
        $doctor->update($updateData);

        $user->update([
            'name' => $data['first_name'].' '.$data['last_name'],
        ]);

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