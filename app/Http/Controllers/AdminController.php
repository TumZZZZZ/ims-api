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
        return redirect()->route('admin.categories')->with('success_message', __('category_created_successfully', ['categoryName' => $request->name]));
    }

    public function deleteCategory(Request $request, $categoryId)
    {
        $this->getService()->deleteCategory($categoryId);
        return response()->json([
            'success' => true,
            'message' => '<strong>'.$request->category_name.'</strong> deleted successfully',
            'code'    => 200,
        ]);
    }

    public function productList(Request $request)
    {
        return view('admin.products.index');
    }
}
