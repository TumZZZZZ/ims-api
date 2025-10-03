<?php

namespace App\Services;

use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SVStore
{
    public function create(array $params)
    {
        # Create Store
        $store = Store::create([
            'name'     => $params['name'],
            'location' => $params['location'],
        ]);

        # Create Admin User for the Store
        User::create([
            'store_id'     => $store->id,
            'first_name'   => $params['user']['first_name'],
            'last_name'    => $params['user']['last_name'],
            'email'        => $params['user']['email'],
            'password'     => Hash::make($params['user']['password']),
            'role'         => 'ADMIN',
            'calling_code' => $params['user']['calling_code'],
            'phone_number' => $params['user']['phone_number'],
        ]);

        return $store;
    }
}
