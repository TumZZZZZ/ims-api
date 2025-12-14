<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApi;
use App\Services\Admin\SVCategory;
use App\Services\Admin\SVProduct;
use Illuminate\Http\Request;

class ProductController extends BaseApi
{
    public function getService()
    {
        return (new SVProduct());
    }

    public function index(Request $request)
    {
        return view('admin.products.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => (new SVCategory)->getAllCategories(),
        ]);
    }

    public function store(Request $request)
    {
        $this->getService()->store($request->all());
        return redirect()->route('admin.products.index')->with('success_message', __('object_created_successfully', ['object' => __('product'), 'object_name' => $request->name]));
    }

    public function edit($productId)
    {
        return view('admin.products.update', [
            'data' => $this->getService()->getById($productId),
            'categories' => (new SVCategory)->getAllCategories(),
        ]);
    }

    public function update(Request $request, $productId)
    {
        $this->getService()->update($productId, $request->all());
        return redirect()->route('admin.products.index')->with('success_message', __('object_updated_successfully', ['object' => __('product'), 'object_name' => $request->name]));
    }

    public function delete(Request $request, $productId)
    {
        $this->getService()->delete($productId);
        return response()->json([
            'success' => true,
            'message' => __('object_deleted_successfully', ['object' => __('product'), 'object_name' => $request->name]),
            'code'    => 200,
        ]);
    }
}
