<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAssign;
use Illuminate\Support\Facades\Auth;

class SVProduct
{
    public function getWithPagination(array $params)
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

    public function store(array $params)
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

    public function getById($id)
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

    public function update($productId, array $params)
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

    public function delete($productId)
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

    public function getAllProducts()
    {
        $user = Auth::user();
        return Product::with(['assign'])
            ->whereHas('assign', function ($query) use ($user) {
                $query->where('branch_id', $user->active_on);
            })
            ->get();
    }
}
