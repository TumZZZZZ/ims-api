<?php

namespace App\Services\SuperAdmin;

use App\Models\User;

class SVUser
{
    public function getWithPagination(array $params)
    {
        $search = $params['search'] ?? null;
        return User::with(['image'])
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', '%'.$search.'%')
                      ->orWhere('last_name', 'like', '%'.$search.'%')
                      ->orWhere('email', 'like', '%'.$search.'%')
                      ->orWhere('phone_number', 'like', '%'.$search.'%');
                });
            })
            ->where('role', '!=', 'SUPER_ADMIN')
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->paginate(10);
    }
}
