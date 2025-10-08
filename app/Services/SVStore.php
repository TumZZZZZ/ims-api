<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SVStore
{
    public function getStores(array $params = [])
    {
        $query = Store::with('image');

        if (isset($params['search']) && $params['search']) {
            $query->where('name', 'like', '%' . $params['search'] . '%');
        }

        $stores = $query->select('id','name','location')->get();

        foreach ($stores as $store) {
            $store->image_url = @$store->image->url ? url($store->image->url) : null;
            unset($store->image);
        }

        return $stores;
    }

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

    public function update($id, array $params)
    {
        $store = Store::find($id);
        if (!$store) {
            throw new \Exception('Store not found');
        }

        $updateData = [
            'name'     => $params['name'],
            'location' => $params['location'],
        ];

        $store->update($updateData);

        Image::updateOrCreate(
            [
                'object_id'  => $store->id,
                'collection' => 'stores',
            ],
            [
                'url' => $params['image'] ?? null,
            ]
        );

        return $store;
    }
}
