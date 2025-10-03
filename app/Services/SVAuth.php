<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

    public function getUserInfo(User $user)
    {
        return [
            'first_name'   => $user->first_name,
            'last_name'    => $user->last_name,
            'email'        => $user->email,
            'role'         => $user->role,
            'calling_code' => $user->calling_code,
            'phone_number' => $user->phone_number,
        ];
    }
}
