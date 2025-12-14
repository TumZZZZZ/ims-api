<?php

namespace App\Services\SuperAdmin;

use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SVMerchant
{
    public function getWithPagination(array $params)
    {
        $search = $params['search'] ?? null;
        return Store::with(['branches','image'])
            ->when($search, function($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
                      ->orWhere('location', 'like', '%'.$search.'%');
            })
            ->where('deleted_at', null)
            ->whereNull('parent_id')
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function store(array $params)
    {
        mongoDBTransaction(function() use ($params) {
            // Create merchant
            $merchant = Store::create([
                'name'      => $params['merchant_name'],
                'location'  => $params['merchant_address'],
                'active'    => 1,
            ]);

            // Save image if exists
            if (request()->hasFile('image')) {
                uploadImage($merchant->_id, 'stores', request()->file('image'));
                unset($params['image']);
            }

            // create merchant admin user
            User::create([
                'merchant_id'    => $merchant->_id,
                'first_name'    => $params['first_name'],
                'last_name'     => $params['last_name'],
                'email'         => $params['email'],
                'phone_number'  => $params['phone_number'],
                'password'      => bcrypt($params['password']),
                'role'          => 'ADMIN',
            ]);

            // Create history
            unset($params['_token']);
            createHistory(Auth::user()->id, __('created_an_object', ['object' => __('merchant')]), $merchant->_id, null, $params);
        });
    }

    public function getById($merchantId)
    {
        $merchant = Store::with(['image'])->where('_id', $merchantId)->first();
        if (!$merchant) {
            abort(404, 'Merchant not found');
        }
        return (object)[
            'id'            => (string)$merchant->_id,
            'image_url'     => $merchant->image->url ?? null,
            'name'          => $merchant->name,
            'address'       => $merchant->location,
        ];
    }

    public function update($merchantId, $params)
    {
        mongoDBTransaction(function() use ($merchantId, $params) {
            $merchant = Store::find($merchantId);

            // Map details for history
            $historyDetails = [
                'old_name'     => $merchant->name,
                'old_address'  => $merchant->location,
                'new_name'     => $params['merchant_name'],
                'new_address'  => $params['merchant_address'],
            ];

            // Save image if exists
            if (request()->hasFile('image')) {
                uploadImage($merchant->_id, 'stores', request()->file('image'));
                unset($params['image']);
            }

            // Update merchant info
            $merchant->name     = $params['merchant_name'];
            $merchant->location = $params['merchant_address'];
            $merchant->save();

            // Create history
            createHistory(Auth::user()->id, __('updated_an_object', ['object' => __('merchant')]), $merchantId, null, $historyDetails);
        });
    }

    public function delete($merchantId)
    {
        mongoDBTransaction(function() use ($merchantId) {
            $merchant = Store::find($merchantId);

            // Soft delete merchant
            $merchant->deleted_at = now();
            $merchant->save();

            // Soft delete all branches
            $merchant->branches()->update(['deleted_at' => now()]);

            // Create history
            createHistory(Auth::user()->id, __('deleted_an_object', ['object' => __('merchant')]), $merchantId, null);
        });
    }

    public function suspendOrActivate($merchantId, $params)
    {
        mongoDBTransaction(function() use ($merchantId, $params) {
            // Update merchant status
            $merchant = Store::find($merchantId);
            $merchant->active = $params['active'];
            $merchant->save();

            // Update all branches status accordingly
            $merchant->branches()->update(['active' => $params['active']]);

            // Create history
            createHistory(Auth::user()->id, __(strtolower($params['action']).'_an_object', ['object' => __('merchant')]), $merchantId, null);
        });
    }
}
