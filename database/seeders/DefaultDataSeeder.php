<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultDataSeeder extends Seeder
{
    public function run()
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

        # Default merchant & branches
        $merchant = [
            'name'     => 'KFC Cambodia',
            'location' => 'Near IU, Sen Sok, Phnom Penh, Cambodia',
            'branches' => [
                [
                    'name'          => 'KFC Riverside',
                    'location'      => 'Riverside, Phnom Penh, Cambodia',
                    'currency_code' => 'KHR',
                ],
                [
                    'name'          => 'KFC Toul Kork',
                    'location'      => 'Toul Kork, Phnom Penh, Cambodia',
                    'currency_code' => 'USD',
                ],
            ],
        ];

        # Merchant
        $newMerchant = Store::firstOrCreate(
            ['name' => $merchant['name']],
            [
                'parent_id'     => null,
                'location'      => $merchant['location'],
                'currency_code' => null,
                'active'        => 1,
            ]
        );

        # Merchant logo
        Image::firstOrCreate(
            [
                'object_id'  => $newMerchant->_id,
                'collection' => 'stores',
            ],
            [
                'url' => asset('storage/images/stores/76e68975715a9.png'),
            ]
        );

        # Branches
        $storeIds[] = $newMerchant->_id;
        foreach ($merchant['branches'] as $branch) {
            $newBranch = Store::firstOrCreate(
                ['name' => $branch['name']],
                [
                    'parent_id'     => $newMerchant->_id,
                    'location'      => $branch['location'],
                    'currency_code' => $branch['currency_code'],
                    'active'        => 1,
                ]
            );
            $storeIds[] = $newBranch->_id;
        }

        # Admin user
        $admin = User::updateOrCreate(
            [
                'email'        => 'jeffjustin178@gmail.com',
                'phone_number' => '0876555653',
            ],
            [
                'store_ids'  => $storeIds,
                'first_name' => "I'm",
                'last_name'  => 'Justin',
                'password'   => Hash::make('Admin1234!'),
                'role'       => 'ADMIN',
            ]
        );

        # User image
        Image::firstOrCreate(
            [
                'object_id'  => $admin->_id,
                'collection' => 'users',
            ],
            [
                'url' => asset('storage/images/users/89e64975717a5.jpg'),
            ]
        );
    }
}
