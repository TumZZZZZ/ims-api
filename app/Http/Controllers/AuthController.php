<?php

namespace App\Http\Controllers;

use App\Services\SVAuth;
use Illuminate\Http\Request;

class AuthController extends BaseApi
{
    public function getService()
    {
        return new SVAuth();
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        try {
            $data = $this->getService()->login($request->all());
            return $this->sendResponse($data, 'Login successful');
        } catch (\Throwable $th) {
            return $this->sendError('Login failed', 500, ['error' => $th->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([], 'Logged out successfully');
    }

    public function profile(Request $request)
    {
        $user = $this->getService()->getUserInfo($request->user());
        return $this->sendResponse($user, 'User profile retrieved successfully');
    }
}
