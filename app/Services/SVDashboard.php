<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SVDashboard
{
    public function getSuperAdminDashboard(array $filters = [])
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
}
