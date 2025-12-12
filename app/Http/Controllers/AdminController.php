<?php

namespace App\Http\Controllers;

use App\Services\Admin\SVAdmin;
use Illuminate\Http\Request;

class AdminController extends BaseApi
{
    public function getService()
    {
        return (new SVAdmin());
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function getCategories(Request $request)
    {
        return view('admin.categories.index', [
            'data' => $this->getService()->getCategories($request->all()),
        ]);
    }

    public function createCategoryForm()
    {
        return view('admin.categories.create', [
            'parentCategories' => $this->getService()->getParentCategories(),
        ]);
    }

    public function storeCategory(Request $request)
    {
        $this->getService()->storeCategory($request->all());
        return redirect()->route('admin.categories')->with('success_message', __('object_created_successfully', ['object' => __('category'), 'object_name' => $request->name]));
    }

    public function deleteCategory(Request $request, $categoryId)
    {
        $this->getService()->deleteCategory($categoryId);
        return response()->json([
            'success' => true,
            'message' => __('object_deleted_successfully', ['object' => __('category'), 'object_name' => $request->name]),
            'code'    => 200,
        ]);
    }

    public function editCategoryForm($categoryId)
    {
        return view('admin.categories.update', [
            'data' => $this->getService()->getCategoryById($categoryId),
            'parentCategories' => $this->getService()->getParentCategories(),
        ]);
    }

    public function updateCategory(Request $request, $categoryId)
    {
        $this->getService()->updateCategory($categoryId, $request->all());
        return redirect()->route('admin.categories')->with('success_message', __('object_updated_successfully', ['object' => __('category'), 'object_name' => $request->name]));
    }

    public function getProducts(Request $request)
    {
        return view('admin.products.index', [
            'data' => $this->getService()->getProducts($request->all()),
        ]);
    }

    public function createProductForm()
    {
        return view('admin.products.create', [
            'categories' => $this->getService()->getAllCategories(),
        ]);
    }

    public function storeProduct(Request $request)
    {
        $this->getService()->storeProduct($request->all());
        return redirect()->route('admin.products')->with('success_message', __('object_created_successfully', ['object' => __('product'), 'object_name' => $request->name]));
    }

    public function editProductForm($productId)
    {
        return view('admin.products.update', [
            'data' => $this->getService()->getProductById($productId),
            'categories' => $this->getService()->getAllCategories(),
        ]);
    }

    public function updateProduct(Request $request, $productId)
    {
        $this->getService()->updateProduct($productId, $request->all());
        return redirect()->route('admin.products')->with('success_message', __('object_updated_successfully', ['object' => __('product'), 'object_name' => $request->name]));
    }

    public function deleteProduct(Request $request, $productId)
    {
        $this->getService()->deleteProduct($productId);
        return response()->json([
            'success' => true,
            'message' => __('object_deleted_successfully', ['object' => __('product'), 'object_name' => $request->name]),
            'code'    => 200,
        ]);
    }
}
