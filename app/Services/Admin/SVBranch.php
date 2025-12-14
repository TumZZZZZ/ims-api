<?php

namespace App\Services\Admin;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class SVBranch
{
    public function getWithPagination(array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;
        return Store::with(['image'])
            ->when($search, function($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('currency_code', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%');
            })
            ->where('parent_id', $user->merchant->id)
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function store(array $params)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($params, $user) {
            // Create branch
            $branch = Store::create([
                'parent_id'     => $user->merchant->id,
                'name'          => $params['name'],
                'location'      => $params['address'] ?? null,
                'currency_code' => $params['currency_code'],
                'active'        => 1,
            ]);

            // Assign user
            $user->push('branch_ids', $branch->id);
            if (!@$user->getActiveBranch()) {
                $user->active_on = $branch->id;
                $user->save();
            }

            // Save image if exists
            if (request()->hasFile('image')) {
                uploadImage($branch->_id, 'stores', request()->file('image'));
                unset($params['image']);
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('created_an_object', ['object' => __('branch')]), @$user->merchant->id, $user->active_on, $params);
        });
    }

    public function getById($id)
    {
        return Store::find($id);
    }

    public function update($id, array $params)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($id, $params, $user) {
            // Create branch
            $branch = Store::find($id);

            // Update store
            $branch->name = $params['name'];
            $branch->location = $params['address'] ?? null;
            $branch->save();

            // Update image if exists
            if (request()->hasFile('image')) {
                uploadImage($branch->_id, 'stores', request()->file('image'));
                unset($params['image']);
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('updated_an_object', ['object' => __('branch')]), @$user->merchant->id, $user->active_on, $params);
        });
    }

    public function delete($id)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($id, $user) {
            $store = Store::find($id);

            // Soft delete store
            $store->deleted_at = now();
            $store->save();

            // Create history
            createHistory($user->_id, __('deleted_an_object', ['object' => __('branch')]), @$user->merchant->id, $user->active_on, [
                'category_id' => (string)$store->_id,
                'name'        => $store->name,
            ]);
        });
    }

    public function closeOrOpen($id, $params)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($id, $params, $user) {
            // Update branch status
            $branch = Store::find($id);
            $branch->active = $params['active'];
            $branch->save();

            // Update all branches status accordingly
            $branch->branches()->update(['active' => $params['active']]);

            // Create history
            createHistory($user->id, __(strtolower($params['action']).'_an_object', ['object' => __('branch')]), @$user->merchant->id, $id);
        });
    }
}
