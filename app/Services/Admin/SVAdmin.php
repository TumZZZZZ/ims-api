<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAssign;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SVAdmin
{
    public function getParentCategories()
    {
        $user = Auth::user();
        return Category::where('parent_id', null)
            ->whereIn('branch_ids', [$user->active_on])
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
            ->where('branch_ids', $user->active_on)
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

    public function getCategoryById($id)
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

    public function updateCategory($categoryId, array $params)
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
            } else {
                removeImage($category->_id, 'categories');
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('updated_an_object', ['object' => __('category')]), @$user->merchant->id, $user->active_on, $params);
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

    public function getProducts(array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;
        return Product::with(['image','categories','assign',])
            ->when($search, function($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('barcode', 'like', '%'.$search.'%');
            })
            ->whereHas('assign', function ($query) use ($user) {
                $query->where('branch_id', $user->active_on);
            })
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function storeProduct(array $params)
    {
        $user = Auth::user();
        mongodbTransaction(function() use ($params, $user) {
            // Create product
            $product = Product::create([
                'name' => $params['name'],
                'sku' => $params['sku'],
                'barcode' => $params['barcode'] ?? null,
                'description' => $params['description'] ?? null
            ]);

            // Save image if exists
            if (request()->hasFile('image')) {
                uploadImage($product->id, 'products', request()->file('image'));
                unset($params['image']);
            }

            // Assign category
            $category = Category::find($params['category_id']);
            $category->product_ids = !empty($category->product_ids) ? $category->product_ids : [];
            $category->product_ids = array_merge($category->product_ids, [$product->id]);
            $category->save();

            // Assign category
            $product->push('category_ids', $category->id);

            // Create product assigns
            foreach ($params['branches'] as $branch) {
                ProductAssign::create([
                    'branch_id' => $branch['branch_id'],
                    'product_id' => $product->id,
                    'price' => convertAmountsToCents($branch['price']),
                    'cost' => convertAmountsToCents($branch['cost']),
                    'quantity' => $branch['stock_quantity'],
                    'threshold' => $branch['threshold'],
                ]);
                // Assign branch
                $category->push('branch_ids', $branch['branch_id']);
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('created_an_object', ['object' => __('product')]), @$user->merchant->id, $user->active_on, $params);
        });
    }

    public function getProductById($id)
    {
        $product = Product::find($id);
        if (!$product) {
            abort(404, 'Product not found');
        }

        $branches = [];
        foreach ($product->assignAll as $assign) {
            $branches[] = (object)[
                'id' => $assign->branch->id,
                'name' => $assign->branch->name,
                'price' => convertCentsToAmounts($assign->price),
                'cost' => convertCentsToAmounts($assign->cost),
                'stock_quantity' => $assign->quantity,
                'threshold' => $assign->threshold,
                'currency_code' => $assign->branch->currency_code,
            ];
        }
        return (object)[
            'id' => $product->id,
            'image_url' => $product->image->url ?? null,
            'name' => $product->name,
            'sku' => $product->sku,
            'barcode' => $product->barcode,
            'description' => $product->description ?? null,
            'category_id' => $product->categories->first()->id,
            'branches' => $branches,
        ];
    }

    public function updateProduct($productId, array $params)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($productId, $params, $user) {
            $product = Product::find($productId);

            // Update product
            $product->name = $params['name'];
            $product->sku = $params['sku'];
            $product->barcode = $params['barcode'] ?? null;
            $product->description = $params['description'] ?? null;
            $product->save();

            // Update image if exists
            if (request()->hasFile('image')) {
                uploadImage($product->_id, 'products', request()->file('image'));
                unset($params['image']);
            } else {
                removeImage($product->_id, 'products');
            }

            // Update all product assign
            $productAssigns = $product->assignAll;
            foreach ($params['branches'] as $branch) {
                $assign = $productAssigns->where('branch_id', $branch['branch_id'])->where('product_id', $product->id)->first();
                $assign->price = convertAmountsToCents($branch['price']);
                $assign->cost = convertAmountsToCents($branch['cost']);
                $assign->quantity = $branch['stock_quantity'];
                $assign->threshold = $branch['threshold'];
                $assign->save();
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('updated_an_object', ['object' => __('category')]), @$user->merchant->id, $user->active_on, $params);
        });
    }

    public function deleteProduct($productId)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($productId, $user) {
            $product = Product::find($productId);
            $now = now();

            // Soft delete product
            $product->deleted_at = $now;
            $product->save();

            // Soft delete product assign
            ProductAssign::where('product_id', $product->id)->delete();

            // Create history
            createHistory($user->_id, __('deleted_an_object', ['object' => __('product')]), @$user->merchant->id, $user->active_on, [
                'name' => $product->name
            ]);
        });
    }

    public function getUsers(array $params)
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

    public function getUserById($id)
    {
        $user = User::find($id);
        return $user;
    }
}
