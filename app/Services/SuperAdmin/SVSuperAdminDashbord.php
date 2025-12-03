<?php

namespace App\Services\SuperAdmin;

use App\Enum\Constants;
use App\Models\History;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SVSuperAdminDashbord
{
    public function getDashboard()
    {
        $collectionStores = DB::getCollection('stores');
        $collectionUsers  = DB::getCollection('users');

        $activatedMerchants = $collectionStores->countDocuments(['parent_id' => null, 'deleted_at' => null]);
        $openBranches       = $collectionStores->countDocuments(['parent_id' => ['$ne' => null], 'deleted_at' => null]);
        $suspendMerchants   = $collectionUsers->countDocuments(['parent_id' => null, 'active' => false, 'deleted_at' => null]);
        $closedBranches     = $collectionUsers->countDocuments(['parent_id' => ['$ne' => null], 'active' => false, 'deleted_at' => null]);
        $totalUsers         = $collectionUsers->countDocuments(['role' => ['$ne' => 'SUPER_ADMIN'], 'deleted_at' => null]);
        $totalAdmins        = $collectionUsers->countDocuments(['role' => 'ADMIN', 'deleted_at' => null]);
        $totalManagers      = $collectionUsers->countDocuments(['role' => 'MANAGER', 'deleted_at' => null]);
        $totalStaffs        = $collectionUsers->countDocuments(['role' => 'STAFF', 'deleted_at' => null]);

        return [
            'activated_merchants'   => $activatedMerchants,
            'open_branches'         => $openBranches,
            'suspended_merchants'   => $suspendMerchants,
            'closed_branches'       => $closedBranches,
            'total_users'           => $totalUsers,
            'total_admins'          => $totalAdmins,
            'total_managers'        => $totalManagers,
            'total_staffs'          => $totalStaffs,
        ];
    }

    public function getMerchants(array $params)
    {
        return Store::with(['branches','image'])
            ->where('deleted_at', null)
            ->whereNull('parent_id')
            ->get()
            ->map(function($store) {
                return (object)[
                    'id'        => (string)$store->_id,
                    'image_url' => $store->image->url ?? null,
                    'name'      => $store->name,
                    'branches'  => implode(', ', $store->branches->pluck('name')->toArray()),
                    'address'   => $store->location,
                    'active'    => $store->active,
                ];
            })
            ->values();
    }

    public function suspendOrActivateMerchant($merchantId, $params)
    {
        Store::where('id', $merchantId)->update(['active' => $params['active']]);
        createHistory(Auth::user()->id, ucfirst($params['action'])." ".$params['merchant_name'], $merchantId);
    }

    public function getBranches(array $params)
    {
        return Store::with(['merchant','image'])
            ->where('deleted_at', null)
            ->whereNotNull('parent_id')
            ->get()
            ->map(function($store) {
                return (object)[
                    'id'            => (string)$store->_id,
                    'image_url'     => $store->image->url ?? null,
                    'name'          => $store->name,
                    'merchant'      => $store->merchant->name ?? 'N/A',
                    'currency_code' => $store->currency_code,
                    'address'       => $store->location,
                    'active'        => $store->merchant->active ? $store->active : 0,
                ];
            })
            ->values();
    }

    public function getUsers(array $params)
    {
        return User::with(['image'])
            ->select('store_ids','name','first_name','last_name','email','calling_code','phone_number','role')
            ->where('role', '!=', 'SUPER_ADMIN')
            ->where('deleted_at', null)
            ->get()
            ->map(function($user) {
                return (object)[
                    'image_url' => @$user->image->url ? url($user->image->url) : null,
                    'user_name' => $user->first_name.' '.$user->last_name,
                    'email'     => $user->email,
                    'phone'     => '+'.$user->calling_code.$user->phone_number,
                    'merchant'  => $user->getMerchant()->name ?? 'Unknown Merchant',
                    'branches'  => $user->getBranches()->implode('name', ', ') ?? 'Unknown Branch',
                    'role'      => Constants::ROLES[$user->role] ?? 'Unknown Role',
                ];
            })
            ->values();
    }

    public function getUserActivities()
    {
        return History::with(['user','store'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function($activity) {
                $store    = $activity->store;
                $merchant = !$activity->store->parent_id ? $store->name : "-";
                $branch   = $activity->store->parent_id ? $store->name : "-";
                return (object)[
                    'username'  => $activity->user->getFullName(),
                    'merchant'  => $merchant,
                    'branch'    => $branch,
                    'role'      => Constants::ROLES[$activity->user->role],
                    'action'    => $activity->action,
                    'date'      => Carbon::parse($activity->created_at)->setTimezone(getTimezone())->format('m/d/Y h:i A'),
                ];
            });
    }
}
