<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApi;
use App\Services\Admin\SVCategory;
use Illuminate\Http\Request;

class CategoryController extends BaseApi
{
    public function getService()
    {
        return (new SVCategory());
    }

    public function index(Request $request)
    {
        return view('admin.categories.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }

    public function create()
    {
        return view('admin.categories.create', [
            'parentCategories' => $this->getService()->getParentCategories(),
        ]);
    }

    public function store(Request $request)
    {
        $this->getService()->store($request->all());
        return redirect()->route('admin.categories.index')->with('success_message', __('object_created_successfully', ['object' => __('category'), 'object_name' => $request->name]));
    }

    public function edit($categoryId)
    {
        return view('admin.categories.update', [
            'data' => $this->getService()->getById($categoryId),
            'parentCategories' => $this->getService()->getParentCategories(),
        ]);
    }

    public function update(Request $request, $categoryId)
    {
        $this->getService()->update($categoryId, $request->all());
        return redirect()->route('admin.categories.index')->with('success_message', __('object_updated_successfully', ['object' => __('category'), 'object_name' => $request->name]));
    }

    public function delete(Request $request, $categoryId)
    {
        $this->getService()->delete($categoryId);
        return response()->json([
            'success' => true,
            'message' => __('object_deleted_successfully', ['object' => __('category'), 'object_name' => $request->name]),
            'code'    => 200,
        ]);
    }
}
