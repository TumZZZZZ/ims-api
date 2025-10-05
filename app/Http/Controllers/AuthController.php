<?php

namespace App\Http\Controllers;

use App\Services\SVAuth;
use Illuminate\Http\Request;

/**
 * @group Authentication
 *
 * To detail for authorization to protect route
 *
 * @authenticated
 * */
class AuthController extends BaseApi
{
    public function getService()
    {
        return new SVAuth();
    }

    /**
     * Login
     *
     * @bodyParam email string required The email of the user. Example: testing@gmail.com
     * @bodyParam password string required The password of the user. Example: 1234567890
     *
     * @responseFile storage/response/auth/login.json
     */
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

    /**
     * Logout
     *
     * @authenticated
     * @responseFile storage/response/auth/logout.json
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([], 'Logged out successfully');
    }

    /**
     * Profile
     *
     * @authenticated
     * @responseFile storage/response/auth/profile.json
     */
    public function profile(Request $request)
    {
        $user = $this->getService()->getUserInfo($request->user());
        return $this->sendResponse($user, 'User profile retrieved successfully');
    }
}
