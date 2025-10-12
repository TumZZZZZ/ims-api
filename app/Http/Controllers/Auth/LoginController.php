<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // For API requests

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login'); // login page
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Call API login (replace API_URL with your API endpoint)
        $response = Http::post(config('app.api_url').'/api/v1/login', [
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        $data = $response->json();

        if ($response->successful() && $data['status'] ?? false) {
            $user = $data['data']['user'];
            session(['api_token' => $data['data']['token']]);
            session(['api_token_type' => $data['data']['token_type']]);
            session(['user' => $user]);

            if ($user['role'] === 'SUPER_ADMIN') {
                return redirect()->route('super-admin.dashboard');
            } elseif ($user['role'] === 'ADMIN') {
                return redirect()->route('admin.dashboard');
            } elseif ($user['role'] === 'MANAGER') {
                return redirect()->route('mamager.dashboard');
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}
