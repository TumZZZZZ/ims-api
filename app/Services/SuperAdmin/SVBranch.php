<?php

namespace App\Services\SuperAdmin;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class SVBranch
{
    public function getWithPagination(array $params)
    {
        $search = $params['search'] ?? null;
        return Store::with(['merchant','image'])
            ->when($search, function($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
                      ->orWhere('location', 'like', '%'.$search.'%')
                      ->orWhereHas('merchant', function($q) use ($search) {
                          $q->where('name', 'like', '%'.$search.'%');
                      });
            })
            ->where('deleted_at', null)
            ->whereNotNull('parent_id')
            ->orderByDesc('created_at')
            ->paginate(10);
    }
}
