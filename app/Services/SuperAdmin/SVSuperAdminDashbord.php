<?php

namespace App\Services\SuperAdmin;

use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SVSuperAdminDashbord
{
    public function getDashboard()
    {
        $collectionStores = DB::getCollection('stores');
        $collectionUsers  = DB::getCollection('users');

        $totalStores   = $collectionStores->countDocuments(['deleted_at' => null]);
        $totalAdmins   = $collectionUsers->countDocuments(['role' => 'ADMIN', 'deleted_at' => null]);
        $totalManagers = $collectionUsers->countDocuments(['role' => 'MANAGER', 'deleted_at' => null]);
        $totalStaffs   = $collectionUsers->countDocuments(['role' => 'STAFF', 'deleted_at' => null]);

        return [
            'total_stores'   => $totalStores,
            'total_admins'   => $totalAdmins,
            'total_managers' => $totalManagers,
            'total_staffs'   => $totalStaffs,
        ];
    }

    public function getStores(array $params)
    {
        return Store::with('image')
            ->select('name','location','currency_code')
            ->where('deleted_at', null)
            ->get()
            ->map(function($store) {
                return (object)[
                    'image_url' => @$store->image->url ? url($store->image->url) : null,
                    'name'      => $store->name,
                    'currency'  => $store->currency_code,
                    'address'   => $store->location,
                ];
            })
            ->values();
    }

    public function getUsers(array $params)
    {
        $roles = [
            'ADMIN'   => 'Admin',
            'MANAGER' => 'Manager',
            'STAFF'   => 'Staff',
        ];
        return User::with(['image','store'])
            ->select('store_id','name','first_name','last_name','email','calling_code','phone_number','role')
            ->where('role', '!=', 'SUPER_ADMIN')
            ->where('deleted_at', null)
            ->get()
            ->map(function($user) use ($roles) {
                return (object)[
                    'image_url' => @$user->image->url ? url($user->image->url) : null,
                    'full_name' => $user->first_name.' '.$user->last_name,
                    'email'     => $user->email,
                    'phone'     => '+'.$user->calling_code.$user->phone_number,
                    'store'     => $user->store->name ?? 'Unknown Store',
                    'role'      => $roles[$user->role] ?? 'Unknown Role',
                ];
            })
            ->values();
    }
}
