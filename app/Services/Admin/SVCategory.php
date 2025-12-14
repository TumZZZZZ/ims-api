<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAssign;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SVCategory
{
    public function getWithPagination(array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;
        return Category::when($search, function($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->where('branch_ids', $user->active_on)
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function store(array $params)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($params, $user) {
            // Create category
            $category = Category::create([
                'name'       => $params['name'],
                'branch_ids' => [$user->active_on],
                'parent_id'  => $params['parent_category_id'] ?? null,
            ]);

            // Save image if exists
            if (request()->hasFile('image')) {
                uploadImage($category->_id, 'categories', request()->file('image'));
                unset($params['image']);
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('created_an_object', ['object' => __('category')]), @$user->merchant->id, $user->active_on, $params);
        });
    }

    public function getById($id)
    {
        $category = Category::find($id);
        if (!$category) {
            abort(404, 'Category not found');
        }
        return (object)[
            'id'            => (string)$category->_id,
            'image_url'     => $category->image->url ?? null,
            'name'          => $category->name,
            'parent_id'     => $category->parent_id,
        ];
    }

    public function update($categoryId, array $params)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($categoryId, $params, $user) {
            $category = Category::find($categoryId);

            // Update category
            $category->name = $params['name'];
            $category->parent_id = $params['parent_category_id'] ?? null;
            $category->save();

            // Update image if exists
            if (request()->hasFile('image')) {
                uploadImage($category->_id, 'categories', request()->file('image'));
                unset($params['image']);
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('updated_an_object', ['object' => __('category')]), @$user->merchant->id, $user->active_on, $params);
        });
    }

    public function delete($categoryId)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($categoryId, $user) {
            $category = Category::find($categoryId);

            // Soft delete category
            $category->deleted_at = now();
            $category->save();

            // Create history
            createHistory($user->_id, __('deleted_an_object', ['object' => __('category')]), @$user->merchant->id, $user->active_on, [
                'category_id' => (string)$category->_id,
                'name'        => $category->name,
            ]);
        });
    }

    public function getAllCategories()
    {
        $user = Auth::user();
        return Category::whereIn('branch_ids', [$user->active_on])
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getParentCategories()
    {
        $user = Auth::user();
        return Category::where('parent_id', null)
            ->whereIn('branch_ids', [$user->active_on])
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->get();
    }
}
