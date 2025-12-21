<?php

namespace App\Services\Inventory;

use App\Enum\Constants;
use App\Mail\SendPurchaseOrderMail;
use App\Models\PurchaseOrder;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SVPurchaseOrder
{
    public function getWithPagination(array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;
        $statusByTab = [
            'closed' => Constants::PURCHASE_ORDER_STATUS_RECEIVED,
            'draft' => Constants::PURCHASE_ORDER_STATUS_IN_REVIEW,
            'sent' => Constants::PURCHASE_ORDER_STATUS_REQUESTED,
            'rejected' => Constants::PURCHASE_ORDER_STATUS_REJECTED,
        ];
        // Get matching branch IDs
        $branchIds = Store::where('name', 'like', "%{$search}%")
            ->pluck('id');

        // Get matching supplier IDs
        $supplierIds = Supplier::where('name', 'like', "%{$search}%")
            ->pluck('id');

        // Sorting
        switch ($params['tab']) {
            case 'closed':
                $sortingField = 'received_date';
                break;
            case 'sent':
                $sortingField = 'requested_date';
                break;
            case 'rejected':
                $sortingField = 'rejected_date';
                break;
            default:
                $sortingField = 'created_at';
                break;
        }

        return PurchaseOrder::with(['branch','supplier'])
            ->when($search, function($q) use ($search, $branchIds, $supplierIds) {
                $q->where(function($q2) use ($search, $branchIds, $supplierIds) {
                    $q2->where('order_number', 'like', "%{$search}%")
                    ->orWhereIn('branch_id', $branchIds)
                    ->orWhereIn('supplier_id', $supplierIds);
                });
            })
            ->where('branch_id', $user->active_on)
            ->where('status', $statusByTab[$params['tab']])
            ->whereNull('deleted_at')
            ->orderByDesc($sortingField)
            ->paginate(10);
    }

    public function getById($id)
    {
        return PurchaseOrder::find($id);
    }

    public function store(array $params)
    {
        $user = Auth::user();
        mongodbTransaction(function() use ($user, $params) {
            $action = strtoupper($params['action']);

            # Convert amount to cent before storing
            $totalAllProduct = 0;
            foreach ($params['items'] as &$item) {
                $totalAllProduct += $item['total_cost'];
                $item['unit_cost'] = convertAmountsToCents($item['unit_cost']);
                $item['total_cost'] = convertAmountsToCents($item['total_cost']);
            }

            # Create purchase order
            $purchaseOrder = PurchaseOrder::create([
                'branch_id' => $user->active_on,
                'user_id' => $user->id,
                'supplier_id' => $params['supplier_id'],
                'order_number' => $params['po_number'],
                'requested_date' => $params['order_date'],
                'status' => $action === 'SAVE' ? Constants::PO_STATUS_IN_REVIEW : Constants::PO_STATUS_REQUESTED,
                'total_cost' => convertAmountsToCents($totalAllProduct),
                'purchase_order_details' => $params['items'],
                'payment_term' => $params['payment_term'],
                'shipping_carrier' => $params['shipping_carrier'],
                'shipping_fee' => convertAmountsToCents($params['shipping_fee'])
            ]);

            if ($action === 'CREATE') {
                $supplierEmail = $purchaseOrder->supplier->email;
                if ($supplierEmail) Mail::to($supplierEmail)->send(new SendPurchaseOrderMail($purchaseOrder));
            }

            // Create history
            createHistory($user->_id, __('created_an_object', ['object' => __('purchase_order')]), @$user->merchant->id, $user->active_on);
        });
    }

    public function getPaymentTerms()
    {
        return [
            'COD',
            'Net 7',
            'Net 15',
            'Net 30',
        ];
    }

    public function getShippingCarrier()
    {
        return [
            'Airline',
            'Trucking',
            'Railway',
            'Ship',
        ];
    }

    public function update($purchaseOrderId, array $params)
    {
        $user = Auth::user();
        mongodbTransaction(function() use ($user, $purchaseOrderId, $params) {
            $purchaseOrder = PurchaseOrder::find($purchaseOrderId);
            $action = strtoupper($params['action']);

            switch ($action) {
                case 'CLOSE':
                    $this->createLedgersByPurchaseOrder($purchaseOrder);
                    $purchaseOrder->status = Constants::PURCHASE_ORDER_STATUS_RECEIVED;
                    $purchaseOrder->save();
                    break;
                    # code...
                    break;
                case 'SAVE': case 'SEND':
                    # Convert amount to cent before storing
                    $totalAllProduct = 0;
                    foreach ($params['items'] as &$item) {
                        $totalAllProduct += $item['total_cost'];
                        $item['unit_cost'] = convertAmountsToCents($item['unit_cost']);
                        $item['total_cost'] = convertAmountsToCents($item['total_cost']);
                    }
                    $purchaseOrder->supplier_id = $params['supplier_id'];
                    $purchaseOrder->total_cost = convertAmountsToCents($totalAllProduct);
                    $purchaseOrder->purchase_order_details = $params['items'];
                    $purchaseOrder->payment_term = $params['payment_term'];
                    $purchaseOrder->shipping_carrier = $params['shipping_carrier'];
                    $purchaseOrder->shipping_fee = convertAmountsToCents($params['shipping_fee']);
                    $purchaseOrder->status = $action === 'SEND' ? Constants::PURCHASE_ORDER_STATUS_REQUESTED : $purchaseOrder->status;
                    $purchaseOrder->save();

                    if ($action === 'SEND') {
                        $supplierEmail = $purchaseOrder->supplier->email;
                        if ($supplierEmail) Mail::to($supplierEmail)->send(new SendPurchaseOrderMail($purchaseOrder));
                    }
                    break;
            }

            // Create ledgers


            // Create history
            createHistory($user->_id, __('updated_an_object', ['object' => __('purchase_order')]), @$user->merchant->id, $user->active_on);
        });
    }

    public function createLedgersByPurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        $user = Auth::user();
        $ledgerParams = [];
        foreach ($purchaseOrder->purchaseOrderDetails() as $item) {
            $productAssign = $item['product']->assign;
            $startingQuantity = $productAssign->quantity;
            $productAssign->quantity = $productAssign->quantity + $item['quantity'];
            $productAssign->save();

            $ledgerParams[] = [
                'branch_id' => $user->active_on,
                'product_id' => $item['product']->id,
                'starting_quantity' => $startingQuantity,
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
                'cost' => $item['total_cost'],
                'type' => Constants::LEDTER_TYPE_PURCHASE_ORDER
            ];
        }
        # Create ledgers
        createLedgers($ledgerParams);
    }

    public function reject($purchaseOrderId, $params)
    {
        $user = Auth::user();
        mongodbTransaction(function() use ($user, $purchaseOrderId, $params) {
            $purchaseOrder = PurchaseOrder::find($purchaseOrderId);
            $purchaseOrder->status = Constants::PURCHASE_ORDER_STATUS_REJECTED;
            $purchaseOrder->reason = $params['reason'];
            $purchaseOrder->save();

            // Create history
            createHistory($user->_id, __('updated_an_object', ['object' => __('purchase_order')]), @$user->merchant->id, $user->active_on);
        });
    }

    public function delete($purchaseOrderId)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($purchaseOrderId, $user) {
            $purchaseOrder = PurchaseOrder::find($purchaseOrderId);

            // Soft delete purchase order
            $purchaseOrder->deleted_at = now();
            $purchaseOrder->save();

            // Create history
            createHistory($user->_id, __('deleted_an_object', ['object' => __('purchase_order')]), @$user->merchant->id, $user->active_on);
        });
    }
}
