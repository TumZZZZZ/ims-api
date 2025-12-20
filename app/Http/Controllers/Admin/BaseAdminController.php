<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SVBaseAdmin;

class BaseAdminController extends Controller
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
