<?php

namespace App\Http\Controllers;

use App\Services\SuperAdmin\SVSuperAdmin;
use Illuminate\Http\Request;

class SuperAdminController extends BaseApi
{
    public function getService()
    {
        return new SVSuperAdmin();
    }

    public function dashboard()
    {
        return view('super-admin.dashboard', [
            'data' => $this->getService()->getDashboard(),
        ]);
    }

    /**
     * Merchant Methods
     */
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

    public function storeMerchant(Request $request)
    {
        $this->getService()->storeMerchant($request->all());
        return redirect()->route('super-admin.merchants')->with('success_message', '<strong>'.$request->merchant_name.'</strong> created successfully');
    }

    public function updateMerchantForm($merchantId)
    {
        return view('super-admin.merchants.update', [
            'data' => $this->getService()->getMerchantById($merchantId),
        ]);
    }

    public function updateMerchant(Request $request, $merchantId)
    {
        $this->getService()->updateMerchant($merchantId, $request->all());
        return redirect()->route('super-admin.merchants')->with('success_message', '<strong>'.$request->merchant_name.'</strong> updated successfully');
    }

    public function deleteMerchant(Request $request, $merchantId)
    {
        $this->getService()->deleteMerchant($merchantId);
        return response()->json([
            'success' => true,
            'message' => '<strong>'.$request->merchant_name.'</strong> deleted successfully',
            'code'    => 200,
        ]);
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

    public function getActivityLogs(Request $request)
    {
        return view('super-admin.activity-logs', [
            'data' => $this->getService()->getUserActivities($request->all()),
        ]);
    }
}
