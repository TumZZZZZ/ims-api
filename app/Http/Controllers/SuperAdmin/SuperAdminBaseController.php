<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\BaseApi;
use App\Services\SuperAdmin\SVBaseSuperAdmin;

class SuperAdminBaseController extends BaseApi
{
    public function getService()
    {
        return new SVBaseSuperAdmin();
    }

    public function dashboard()
    {
        return view('super-admin.dashboard', [
            'data' => $this->getService()->getDashboard(),
        ]);
    }
}
