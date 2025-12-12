<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAssign;
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
            createHistory($user->_id, __('created_an_object', ['object' => __('category')]), $user->active_on, $params);
        });
    }

    public function getCategoryById($categoryId)
    {
        $category = Category::where('_id', $categoryId)->first();
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
            createHistory($user->_id, __('updated_an_object', ['object' => __('category')]), $user->active_on, $params);
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
            createHistory($user->_id, __('deleted_an_object', ['object' => __('category')]), $user->active_on, [
                'category_id' => (string)$category->_id,
                'name'        => $category->name,
            ]);
        });
    }

    public function getAllCategories()
    {
        $user = Auth::user();
        return Category::where('store_id', $user->getMerchant()->id)
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
                $query->where('store_id', $user->active_on);
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
                'store_id' => $user->getMerchant()->id,
                'name' => $params['name'],
                'barcode' => $params['barcode'],
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

            // Create product assigns
            foreach ($params['branches'] as $branch) {
                ProductAssign::create([
                    'store_id' => $branch['branch_id'],
                    'product_id' => $product->id,
                    'price' => convertAmountsToCents($branch['price']),
                    'cost' => convertAmountsToCents($branch['cost']),
                    'quantity' => $branch['stock_quantity'],
                    'threshold' => $branch['threshold'],
                ]);
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('created_an_object', ['object' => __('product')]), $user->active_on, $params);
        });
    }

    public function getProductById($productId)
    {
        $product = Product::where('id', $productId)->first();
        if (!$product) {
            abort(404, 'Product not found');
        }

        $productAssign = $product->assign;
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
            $product->barcode = $params['barcode'];
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
                $assign = $productAssigns->where('store_id', $branch['branch_id'])->where('product_id', $product->id)->first();
                $assign->price = convertAmountsToCents($branch['price']);
                $assign->cost = convertAmountsToCents($branch['cost']);
                $assign->quantity = $branch['stock_quantity'];
                $assign->threshold = $branch['threshold'];
                $assign->save();
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('updated_an_object', ['object' => __('category')]), $user->active_on, $params);
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
            createHistory($user->_id, __('deleted_an_object', ['object' => __('product')]), $user->active_on, [
                'name' => $product->name
            ]);
        });
    }
}
