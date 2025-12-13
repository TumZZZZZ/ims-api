<?php

namespace App\Services\SuperAdmin;

use App\Models\History;

class SVActivityLog
{
    public function getWithPagination(array $params)
    {
        $search = $params['search'] ?? null;
        return History::with(['user','merchant','branch'])
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('action', 'like', '%'.$search.'%')
                      ->orWhereHas('user', function($qu) use ($search) {
                          $qu->where('first_name', 'like', '%'.$search.'%')
                             ->orWhere('last_name', 'like', '%'.$search.'%');
                      })
                      ->orWhereHas('merchant', function($qu) use ($search) {
                          $qu->where('name', 'like', '%'.$search.'%');
                      })
                      ->orWhereHas('branch', function($qu) use ($search) {
                          $qu->where('name', 'like', '%'.$search.'%');
                      });
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);
    }
}
