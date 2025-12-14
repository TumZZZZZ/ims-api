<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SVPurchaseOrder
{
    public function getWithPagination(array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;
        return User::with(['image'])
            ->when($search, function($query, $search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('first_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone_number', 'like', '%'.$search.'%');
            })
            ->where('merchant_id', $user->merchant->id)
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function getById($id)
    {
        $user = User::find($id);
        return $user;
    }
}
