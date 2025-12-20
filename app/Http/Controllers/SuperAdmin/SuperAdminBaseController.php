<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdmin\SVBaseSuperAdmin;

class SuperAdminBaseController extends Controller
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
