<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class SVAdmin
{
    public function getParentCategories()
    {
        $user = Auth::user();
        return Category::where('parent_id', null)
            ->where('store_id', $user->getMerchant()->id)
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getCategories(array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;
        return Category::when($search, function($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->where('store_id', $user->getMerchant()->id)
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function storeCategory(array $params)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($params, $user) {
            // Create category
            $category = Category::create([
                'name'       => $params['name'],
                'store_id'   => $user->getMerchant()->id,
                'parent_id'  => $params['parent_category_id'] ?? null,
            ]);

            // Save image if exists
            if (request()->hasFile('image')) {
                uploadImage($category->_id, 'categories', request()->file('image'));
                unset($params['image']);
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, 'Created category <strong>'.$params['name'].'</strong>', $user->active_on, $params);
        });
    }

    public function deleteCategory($categoryId)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($categoryId, $user) {
            $category = Category::find($categoryId);

            // Soft delete category
            $category->deleted_at = now();
            $category->save();

            // Create history
            createHistory($user->_id, 'Deleted category <strong>'.$category->name.'</strong>', $user->active_on, [
                'category_id' => (string)$category->_id,
                'name'        => $category->name,
            ]);
        });
    }
}
