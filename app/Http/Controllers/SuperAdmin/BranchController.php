<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdmin\SVBranch;
use Illuminate\Http\Request;

class BranchController extends Controller
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
