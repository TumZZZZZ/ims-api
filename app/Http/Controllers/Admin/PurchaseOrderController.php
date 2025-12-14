<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApi;
use App\Services\Admin\SVPurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends BaseApi
{
    public function getService()
    {
        return (new SVPurchaseOrder());
    }

    public function index(Request $request)
    {
        return view('admin.purchase-orders.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'roles' => getRoles(),
            'branches' => Auth::user()->getBranches(),
        ]);
    }

    public function edit($userId)
    {
        return view('admin.users.update', [
            'data' => $this->getService()->getById($userId),
            'roles' => getRoles(),
            'branches' => Auth::user()->getBranches(),
        ]);
    }
}
