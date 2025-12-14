<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\BaseApi;
use App\Services\Inventory\SVPurchaseOrder;
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

    public function closed(Request $request)
    {
        $tab = 'closed';
        $request->merge(['tab' => $tab]);
        return view('admin.purchase-orders.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ])->with('activeTab', $tab);
    }

    public function draft(Request $request)
    {
        $tab = 'draft';
        $request->merge(['tab' => $tab]);
        return view('admin.purchase-orders.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ])->with('activeTab', $tab);
    }

    public function sent(Request $request)
    {
        $tab = 'sent';
        $request->merge(['tab' => $tab]);
        return view('admin.purchase-orders.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ])->with('activeTab', $tab);
    }

    public function rejected(Request $request)
    {
        $tab = 'rejected';
        $request->merge(['tab' => $tab]);
        return view('admin.purchase-orders.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ])->with('activeTab', $tab);
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
