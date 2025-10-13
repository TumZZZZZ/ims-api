<?php

namespace App\Http\Controllers;

use App\Services\SuperAdmin\SVSuperAdminDashbord;
use Illuminate\Http\Request;

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

    public function listStore(Request $request)
    {
        return view('super-admin.stores.index', [
            'data' => $this->getService()->getStores($request->all()),
        ]);
    }

    public function listUser(Request $request)
    {
        return view('super-admin.users.index', [
            'data' => $this->getService()->getUsers($request->all()),
        ]);
    }
}
