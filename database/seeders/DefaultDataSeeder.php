<?php

namespace Database\Seeders;

use App\Enum\Constants;
use App\Models\Image;
use App\Models\Meta;
use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultDataSeeder extends Seeder
{
    public function run()
    {
        mongodbTransaction(function() {
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
                        'name'          => 'Phnom Penh KFC',
                        'location'      => 'Sen Sok, Phnom Penh, Cambodia',
                        'currency_code' => 'USD',
                    ],
                    [
                        'name'          => 'Prey Veng KFC',
                        'location'      => 'Prey Veng, Cambodia',
                        'currency_code' => 'KHR',
                    ]
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

            # Branches
            $branchIds = [];
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
                $branchIds[] = $newBranch->_id;
            }

            # Admin user
            $admin = User::firstOrCreate(
                [
                    'merchant_id'  => $newMerchant->_id,
                    'email'        => 'admin@gmail.com',
                    'phone_number' => '0876555653',
                ],
                [
                    'branch_ids' => $branchIds,
                    'first_name' => "I'm",
                    'last_name'  => 'Admin',
                    'password'   => Hash::make('Admin1234!'),
                    'role'       => 'ADMIN',
                ]
            );

            # User image
            $createParams = [
                'object_id'  => $admin->_id,
                'collection' => 'users',
            ];
            $updateparams = $createParams;
            Image::firstOrCreate($createParams, $updateparams);

            # Payment Method
            $paymentMethods = [
                Constants::PAYMENT_TYPE_CASH,
                Constants::PAYMENT_TYPE_ABA,
                Constants::PAYMENT_TYPE_ACLEDA,
                Constants::PAYMENT_TYPE_WING,
                Constants::PAYMENT_TYPE_FTB,
                Constants::PAYMENT_TYPE_SATHAPANA,
            ];
            foreach ($paymentMethods as $paymentMethod) {
                Meta::firstOrCreate(
                [
                    'key' => Constants::PAYMENT_TYPE,
                    'value' => $paymentMethod,
                ],
                [
                    'object_id' => null,
                ]);
            }
        });
    }
}
