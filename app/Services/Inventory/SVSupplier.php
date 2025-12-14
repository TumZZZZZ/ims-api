<?php

namespace App\Services\Inventory;

use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class SVSupplier
{
    public function getWithPagination(array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;
        return Supplier::with(['merchant'])
            ->when($search, function($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
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
        return Supplier::find($id);
    }

    public function store(array $params)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($params, $user) {
            // Create supplier
            Supplier::create([
                'merchant_id' => $user->merchant->id,
                'name' => $params['name'],
                'email' => $params['email'],
                'phone_number' => $params['phone_number'],
                'address' => $params['address'],
            ]);

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('created_an_object', ['object' => __('supplier')]), @$user->merchant->id, $user->active_on, $params);
        });
    }

    public function update($id, array $params)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($id, $params, $user) {
            // Create supplier
            $supplier = Supplier::find($id);

            // Update supplier
            $supplier->merchant_id = $user->merchant->id;
            $supplier->name = $params['name'];
            $supplier->email = $params['email'];
            $supplier->phone_number = $params['phone_number'];
            $supplier->address = $params['address'];
            $supplier->save();

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('updated_an_object', ['object' => __('supplier')]), @$user->merchant->id, $user->active_on, $params);
        });
    }

    public function delete($id)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($id, $user) {
            $supplier = Supplier::find($id);

            // Soft delete supplier
            $supplier->deleted_at = now();
            $supplier->save();

            // Create history
            createHistory($user->_id, __('deleted_an_object', ['object' => __('supplier')]), @$user->merchant->id, $user->active_on);
        });
    }
}
