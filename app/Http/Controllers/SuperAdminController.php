<?php

namespace App\Http\Controllers;

use App\Services\SuperAdmin\SVSuperAdminDashbord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SuperAdminController extends BaseApi
{
    public function getService()
    {
        return new SVSuperAdminDashbord();
    }

    public function dashboard()
    {
        return view('super-admin.dashboard', [
            'data' => $this->getService()->getDashboard(),
        ]);
    }

    public function getMerchants(Request $request)
    {
        return view('super-admin.merchants.index', [
            'data' => $this->getService()->getMerchants($request->all()),
        ]);
    }

    public function getBranches(Request $request)
    {
        return view('super-admin.branches.index', [
            'data' => $this->getService()->getBranches($request->all()),
        ]);
    }

    public function listUser(Request $request)
    {
        return view('super-admin.users.index', [
            'data' => $this->getService()->getUsers($request->all()),
        ]);
    }
}
