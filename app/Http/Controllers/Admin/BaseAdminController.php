<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApi;
use App\Services\Admin\SVBaseAdmin;

class BaseAdminController extends BaseApi
{
    public function getService()
    {
        return (new SVBaseAdmin());
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
