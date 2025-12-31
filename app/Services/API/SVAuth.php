<?php

namespace App\Services\API;

use App\Enum\Constants;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SVAuth
{
    public function login(array $credential)
    {
        $email = $credential['email'];
        $password = $credential['password'];

        $user = User::where('email', $email)->where('role', Constants::ROLE_STAFF)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw new \Exception('Invalid credentials', 401);
        }

        $branch = $user->getBranches()->first();

        if (!$branch->active) {
            throw new \Exception('This user was suspended');
        }

        # Assingn active on
        $user->active_on = $branch->id;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;
        $token = str_replace('|', '', $token);

        $token = trim($token);

        return [
            'token'         => $token,
            'token_type'    => 'Bearer',
            'user'          => $this->getUserInfo($user),
        ];
    }

    public function logout($user)
    {
        # Free user
        $user->active_on = null;
        $user->save();
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
