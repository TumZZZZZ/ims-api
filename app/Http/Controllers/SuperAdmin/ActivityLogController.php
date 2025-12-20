<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdmin\SVActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
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
