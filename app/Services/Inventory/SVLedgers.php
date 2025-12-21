<?php

namespace App\Services\Inventory;

use App\Models\Ledger;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class SVLedgers
{
    public function getWithPagination(array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;

        $branchIds = Store::where('name', 'like', "%{$search}%")
            ->pluck('id');

        $productIds = Product::where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
            ->orWhere('sku', 'like', "%{$search}%");
        })->pluck('id');

        return Ledger::with(['branch','product'])
            ->when($search, function ($q) use ($branchIds, $productIds, $search) {
                $q->where(function($q2) use ($branchIds, $productIds, $search) {
                    $q2->whereIn('branch_id', $branchIds)
                    ->orWhereIn('product_id', $productIds)
                    ->orWhere('type', 'like', "%{$search}%");
                });
            })
            ->where('branch_id', $user->active_on)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate(10);
    }
}
