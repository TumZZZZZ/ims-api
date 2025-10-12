<?php

namespace App\Http\Controllers;

class AdminController extends BaseApi
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
