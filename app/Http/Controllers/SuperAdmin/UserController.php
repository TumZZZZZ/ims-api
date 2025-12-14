<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\BaseApi;
use App\Services\SuperAdmin\SVUser;
use Illuminate\Http\Request;

class UserController extends BaseApi
{
    public function getService()
    {
        return new SVUser();
    }

    public function index(Request $request)
    {
        return view('super-admin.users.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }
}
