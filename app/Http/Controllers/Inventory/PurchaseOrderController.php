<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Services\Admin\SVProduct;
use App\Services\Inventory\SVPurchaseOrder;
use App\Services\Inventory\SVSupplier;
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
            'suppliers' => (new SVSupplier)->getAllSuppliers(),
            'products' => (new SVProduct)->getAllProducts(),
            'payment_terms' => $this->getService()->getPaymentTerms(),
            'shipping_carrier' => $this->getService()->getShippingCarrier(),
        ]);
    }

    public function edit($purchaseOrderId)
    {
        return view('inventory.purchase-orders.update', [
            'data' => $this->getService()->getById($purchaseOrderId),
            'suppliers' => (new SVSupplier)->getAllSuppliers(),
            'products' => (new SVProduct)->getAllProducts(),
            'payment_terms' => $this->getService()->getPaymentTerms(),
            'shipping_carrier' => $this->getService()->getShippingCarrier(),
        ]);
    }

    public function store(Request $request)
    {
        $this->getService()->store($request->all());
        return redirect()->route('inventory.purchase-orders.'.($request->action == 'save' ? 'draft' : 'sent'))->with('success_message', __('object_created_successfully', ['object' => __('purchase_order'), 'object_name' => $request->po_number]));
    }

    public function viewDetails($purchaseOrderId)
    {
        return view('inventory.purchase-orders.view-details', [
            'data' => $this->getService()->getById($purchaseOrderId)
        ]);
    }

    public function update(Request $request, $purchaseOrderId)
    {
        $this->getService()->update($purchaseOrderId, $request->all());
        switch ($request->action) {
            case 'save':
                $suffix = "draft";
                break;
            case 'send':
                $suffix = "sent";
                break;
            default:
                $suffix = "closed";
                break;
        }
        return redirect()->route('inventory.purchase-orders.'.$suffix)->with('success_message', __('object_updated_successfully', ['object' => __('purchase_order'), 'object_name' => $request->po_number]));
    }

    public function reject(Request $request, $purchaseOrderId)
    {
        $this->getService()->reject($purchaseOrderId, $request->all());
        return redirect()->route('inventory.purchase-orders.rejected')->with('success_message', __('object_updated_successfully', ['object' => __('purchase_order'), 'object_name' => $request->po_number]));
    }

    public function delete(Request $request, $purchaseOrderId)
    {
        $this->getService()->delete($purchaseOrderId);
        return response()->json([
            'success' => true,
            'message' => __('object_deleted_successfully', ['object' => __('branch'), 'object_name' => $request->name]),
            'code'    => 200,
        ]);
    }
}
