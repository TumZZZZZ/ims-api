<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Services\Admin\SVProduct;
use App\Services\Inventory\SVPurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function getService()
    {
        return (new SVPurchaseOrder());
    }

    public function closed(Request $request)
    {
        $tab = 'closed';
        $request->merge(['tab' => $tab]);
        return view('inventory.purchase-orders.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ])->with('activeTab', $tab);
    }

    public function draft(Request $request)
    {
        $tab = 'draft';
        $request->merge(['tab' => $tab]);
        return view('inventory.purchase-orders.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ])->with('activeTab', $tab);
    }

    public function sent(Request $request)
    {
        $tab = 'sent';
        $request->merge(['tab' => $tab]);
        return view('inventory.purchase-orders.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ])->with('activeTab', $tab);
    }

    public function rejected(Request $request)
    {
        $tab = 'rejected';
        $request->merge(['tab' => $tab]);
        return view('inventory.purchase-orders.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ])->with('activeTab', $tab);
    }

    public function create()
    {
        return view('inventory.purchase-orders.create', [
            'po_number' => PurchaseOrder::generatePONumber(),
            'products' => (new SVProduct)->getAllProducts(),
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

    public function store(Request $request)
    {
        return $this->getService()->store($request->all());
        return redirect()->route('inventory.suppliers.index')->with('success_message', __('object_created_successfully', ['object' => __('purchase_order'), 'object_name' => $request->po_number]));
    }
}
