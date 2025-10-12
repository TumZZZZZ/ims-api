<?php

namespace App\Http\Controllers;

class SuperAdminController extends BaseApi
{
    public function dashboard()
    {
        return view('super-admin.dashboard');
    }
}
