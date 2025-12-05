<?php

namespace App\Services\SuperAdmin;

use App\Enum\Constants;
use App\Models\History;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class SVSuperAdmin
{
    public function getDashboard()
    {
        $collectionStores = DB::getCollection('stores');
        $collectionUsers  = DB::getCollection('users');

        $activatedMerchants = $collectionStores->countDocuments(['parent_id' => null, 'active' => 1, 'deleted_at' => null]);
        $openBranches       = $collectionStores->countDocuments(['parent_id' => ['$ne' => null], 'active' => 1, 'deleted_at' => null]);
        $suspendMerchants   = $collectionStores->countDocuments(['parent_id' => null, 'active' => 0, 'deleted_at' => null]);
        $closedBranches     = $collectionStores->countDocuments(['parent_id' => ['$ne' => null], 'active' => 0, 'deleted_at' => null]);
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
            ->orderByDesc('created_at')
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

    public function storeMerchant(array $params)
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
                'store_ids'     => [$merchant->_id],
                'first_name'    => $params['first_name'],
                'last_name'     => $params['last_name'],
                'email'         => $params['email'],
                'phone_number'  => $params['phone_number'],
                'password'      => bcrypt($params['password']),
                'role'          => 'ADMIN',
            ]);

            // Create history
            unset($params['_token']);
            createHistory(Auth::user()->id, 'Created '.$params['merchant_name'], $merchant->_id, $params);
        });
    }

    public function getMerchantById($merchantId)
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

    public function updateMerchant($merchantId, $params)
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
            createHistory(Auth::user()->id, 'Updated '.$params['merchant_name'], $merchantId, $historyDetails);
        });
    }

    public function deleteMerchant($merchantId)
    {
        mongoDBTransaction(function() use ($merchantId) {
            $merchant = Store::find($merchantId);

            // Soft delete merchant
            $merchant->deleted_at = now();
            $merchant->save();

            // Soft delete all branches
            $merchant->branches()->update(['deleted_at' => now()]);

            // Create history
            createHistory(Auth::user()->id, 'Deleted '.$merchant->name, $merchantId);
        });
    }

    public function suspendOrActivateMerchant($merchantId, $params)
    {
        mongoDBTransaction(function() use ($merchantId, $params) {
            // Update merchant status
            $merchant = Store::find($merchantId);
            $merchant->active = $params['active'];
            $merchant->save();

            // Update all branches status accordingly
            $merchant->branches()->update(['active' => $params['active']]);

            // Create history
            createHistory(Auth::user()->id, ucfirst($params['action'])." ".$params['merchant_name'], $merchantId);
        });
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
                    'active'        => $store->active,
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
                    'name'      => $user->first_name.' '.$user->last_name,
                    'email'     => $user->email,
                    'phone'     => $user->phone_number,
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
