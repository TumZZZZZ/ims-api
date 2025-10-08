<?php

namespace App\Services;

use App\Mail\SendOTPMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SVAuth
{
    public function login(array $credential)
    {
        $email = $credential['email'];
        $password = $credential['password'];

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $token = str_replace('|', '', $token);
        $token = trim($token);

        return [
            'token'         => $token,
            'token_type'    => 'Bearer',
            'user'          => $this->getUserInfo($user),
        ];
    }

    public function getUserInfo(User $user, ?array $fields = [])
    {
        $info = [
            'id'           => $user->id,
            'first_name'   => $user->first_name,
            'last_name'    => $user->last_name,
            'email'        => $user->email,
            'role'         => $user->role,
            'calling_code' => $user->calling_code,
            'phone_number' => $user->phone_number,
        ];

        if (!empty($fields)) {
            return array_intersect_key($info, array_flip($fields));
        }

        return $info;
    }

    public function updateProfile($id, $params)
    {
        $user = User::find($id);
        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->first_name   = $params['first_name'];
        $user->last_name    = $params['last_name'];
        $user->email        = $params['email'];
        $user->calling_code = $params['calling_code'];
        $user->phone_number = $params['phone_number'];
        $user->save();

        return $this->getUserInfo($user);
    }

    public function sendMailVerification($email)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new \Exception('User not registered!');
        }
        $otp = rand(100000, 999999);
        $user->verify_otp = $otp;
        $user->save();

        Mail::to($email)->send(new SendOTPMail($otp));
    }

    public function verifyOTP($otp)
    {
        $otp  = (int) $otp;
        $user = User::where('verify_otp', $otp)->first();
        if (!$user) {
            throw new \Exception('Invalid OTP code!');
        }
        $user->verify_otp = null;
        $user->save();

        return $this->getUserInfo($user, ['email']);
    }

    public function resetPassword($params)
    {
        $user = User::where('email', $params['email'])->first();
        $user->password = Hash::make($params['new_password']);
        $user->save();
    }
}
