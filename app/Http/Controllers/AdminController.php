<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends BaseApi
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function productList(Request $request)
    {
        return view('admin.products.index');
    }
}
