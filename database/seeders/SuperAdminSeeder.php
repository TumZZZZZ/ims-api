<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        # Super Admin
        User::firstOrCreate(
            [
                'email' => 'superadmin@gmail.com'
            ],
            [
                'first_name'   => 'Super',
                'last_name'    => 'Admin',
                'password'     => Hash::make(config('app.super_admin_password')),
                'role'         => 'SUPER_ADMIN',
                'calling_code' => '855',
                'phone_number' => '987654321',
            ]
        );
    }
}
