<?php

namespace App\Http\Controllers;

use App\Services\SVDashboard;
use Illuminate\Http\Request;

/**
 * @group Super Admin
 *
 * To detail for Super Admin
 *
 * @authenticated
 * */
class SuperAdminDashboardController extends BaseApi
{
    public function getService()
    {
        return new SVDashboard();
    }

    /**
     * Super Admin Dashboard
     *
     * @authenticated
     * @responseFile storage/response/super-admin/dashboard.json
     */
    public function index(Request $request)
    {
        try {
            $data = $this->getService()->getSuperAdminDashboard($request->all());
            return $this->sendResponse($data, 'Super Admin dashboard data retrieved successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('Failed to retrieve Super Admin dashboard data', 500, ['error' => $th->getMessage()]);
        }
    }
}
