<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\BaseApi;
use App\Services\SuperAdmin\SVBranch;
use Illuminate\Http\Request;

class BranchController extends BaseApi
{
    public function getService()
    {
        return new SVBranch();
    }

    public function index(Request $request)
    {
        return view('super-admin.branches.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }
}
