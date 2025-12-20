<?php

namespace App\Services\API;

use App\Mail\SendOTPMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
            throw new \Exception('Invalid credentials', 401);
        }

        if ($user->active_on) {
            throw new \Exception('User already logged in another device');
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $token = str_replace('|', '', $token);

        $token = trim($token);

        # Assingn active on
        $user->active_on = $user->getBranches()->first()->id;
        $user->save();

        return [
            'token'         => $token,
            'token_type'    => 'Bearer',
            'user'          => $this->getUserInfo($user),
        ];
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return [];
    }

    public function getUserInfo(User $user, ?array $fields = [])
    {
        $info = [
            'id'           => $user->id,
            'image_url'    => $user->image->url ?? null,
            'first_name'   => $user->first_name,
            'last_name'    => $user->last_name,
            'email'        => $user->email,
            'role'         => $user->role,
            'calling_code' => $user->calling_code,
            'phone_number' => $user->phone_number,
            'active_on'    => $user->active_on,
        ];

        if (!empty($fields)) {
            return array_intersect_key($info, array_flip($fields));
        }

        return $info;
    }
}
