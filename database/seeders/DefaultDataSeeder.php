<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultDataSeeder extends Seeder
{
    public function run()
    {
        // 🏬 Create or fetch default store
        $store = Store::firstOrCreate(
            ['name' => '24/7 Supermarket'],
            ['location' => 'Near IU, Sen Sok, Phnom Penh, Cambodia']
        );

        // 👩‍💼 Default users
        $users = [
            [
                'email'       => 'admin@gmail.com',
                'first_name'  => 'Admin',
                'last_name'   => 'User',
                'password'    => Hash::make('Admin1234!'),
                'role'        => 'ADMIN',
                'calling_code'=> '855',
                'phone_number'=> '857585745',
            ],
            [
                'email'       => 'manager@gmail.com',
                'first_name'  => 'Manager',
                'last_name'   => 'User',
                'password'    => Hash::make('Manager1234!'),
                'role'        => 'MANAGER',
                'calling_code'=> '855',
                'phone_number'=> '876555653',
            ],
            [
                'email'       => 'staff@gmail.com',
                'first_name'  => 'Staff',
                'last_name'   => 'User',
                'password'    => Hash::make('Staff1234!'),
                'role'        => 'STAFF',
                'calling_code'=> '855',
                'phone_number'=> '857584756',
            ],
        ];

        // 🧑‍🤝‍🧑 Create each user if not exists
        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']], // unique by email
                array_merge($data, ['store_id' => $store->_id])
            );
        }
    }
}
