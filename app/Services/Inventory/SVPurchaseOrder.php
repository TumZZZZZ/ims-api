<?php

namespace App\Services\Inventory;

use App\Enum\Constants;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

class SVPurchaseOrder
{
    public function getWithPagination(array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;
        $statusByTab = [
            'closed' => Constants::PURCHASE_ORDER_STATUS_CLOSED,
            'draft' => Constants::PURCHASE_ORDER_STATUS_DRAFT,
            'sent' => Constants::PURCHASE_ORDER_STATUS_SENT,
            'rejected' => Constants::PURCHASE_ORDER_STATUS_REJECTED,
        ];
        return PurchaseOrder::with(['branch','supplier'])
            ->when($search, function($query, $search) {
                $query->where('order_number', 'like', '%'.$search.'%')
                    ->orWhereHas('branch', function($qu) use ($search) {
                          $qu->where('name', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('supplier', function($qu) use ($search) {
                          $qu->where('name', 'like', '%'.$search.'%');
                    });
            })
            ->where('branch_id', $user->active_on)
            ->where('status', $statusByTab[$params['tab']])
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function getById($id)
    {
        return PurchaseOrder::find($id);
    }
}
