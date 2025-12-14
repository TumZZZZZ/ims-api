<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\BaseApi;
use App\Services\SuperAdmin\SVActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends BaseApi
{
    public function getService()
    {
        return new SVActivityLog();
    }

    public function index(Request $request)
    {
        return view('super-admin.activity-logs', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }
}
