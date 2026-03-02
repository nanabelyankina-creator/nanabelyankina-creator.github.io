<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function block(User $user)
    {
        $user->is_blocked = true;
        $user->save();

        return back()->with('success', "Пользователь #{$user->id} заблокирован.");
    }

    public function unblock(User $user)
    {
        $user->is_blocked = false;
        $user->save();

        return back()->with('success', "Пользователь #{$user->id} разблокирован.");
    }
}