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

    /**
     * Send Mail Verification
     *
     * @bodyParam email string required The email of the user. Example: example@gmail.com
     *
     * @responseFile storage/response/success.json
     */
    public function sendMailVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $this->getService()->sendMailVerification($request->get('email'));
            return $this->sendResponse([], 'Verification email sent successfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 500, ['error' => $th->getMessage()]);
        }
    }

    /**
     * Verify OTP
     *
     * @bodyParam otp string required The code that sent to mail. Example: 123456
     *
     * @responseFile storage/response/success.json
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|min:6|max:6',
        ]);

        try {
            $user = $this->getService()->verifyOTP($request->get('otp'));
            return $this->sendResponse($user, 'Verified successfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 500, ['error' => $th->getMessage()]);
        }
    }

    /**
     * Reset Password
     *
     * @bodyParam email string required The email of the user. Example: testing@gmail.com
     * @bodyParam new_password string required The new password of the user. Example: 1234567890
     * @bodyParam confirm_password string required The confirmation of the new password. Example: 1234567890
     *
     * @responseFile storage/response/success.json
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'             => 'required|email',
            'new_password'      => 'required|min:8|max:20',
            'confirm_password'  => 'required|same:new_password',
        ]);

        try {
            $this->getService()->resetPassword($request->all());
            return $this->sendResponse([], 'Password reset successfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 500, ['error' => $th->getMessage()]);
        }
    }
}
