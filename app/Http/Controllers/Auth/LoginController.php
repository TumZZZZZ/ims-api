<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $email    = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        session(['user' => $user]);

        if ($user->role === 'SUPER_ADMIN') {
            return redirect()->route('super-admin.dashboard');
        } elseif ($user->role === 'ADMIN') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'MANAGER') {
            return redirect()->route('manager.dashboard');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}
