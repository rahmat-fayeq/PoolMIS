<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => User::all(),
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return to_route('users.index')->with('success', 'User Registered Successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return to_route('users.index')->with('success', 'User Deleted Successfully!');
    }
}
