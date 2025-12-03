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

    public function getMerchants(Request $request)
    {
        return view('super-admin.merchants.index', [
            'data' => $this->getService()->getMerchants($request->all()),
        ]);
    }

    public function createMerchantForm()
    {
        return view('super-admin.merchants.create');
    }

    public function suspendOrActivate(Request $request, $merchantId)
    {
        $this->getService()->suspendOrActivateMerchant($merchantId, $request->all());
        return back()->with('success_message', $request->merchant_name . ' successfully ' . $request->action);
    }

    public function getBranches(Request $request)
    {
        return view('super-admin.branches.index', [
            'data' => $this->getService()->getBranches($request->all()),
        ]);
    }

    public function getUsers(Request $request)
    {
        return view('super-admin.users.index', [
            'data' => $this->getService()->getUsers($request->all()),
        ]);
    }

    public function getActivityLogs()
    {
        return view('super-admin.activity-logs', [
            'data' => $this->getService()->getUserActivities(),
        ]);
    }
}
