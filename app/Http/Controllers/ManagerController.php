<?php

namespace App\Http\Controllers;

class ManagerController extends BaseApi
{
    public function dashboard()
    {
        return view('manager.dashboard');
    }
}
