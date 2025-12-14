<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\BaseApi;
use App\Services\Inventory\SVSupplier;
use Illuminate\Http\Request;

class SupplierController extends BaseApi
{
    public function getService()
    {
        return (new SVSupplier());
    }

    public function index(Request $request)
    {
        return view('admin.suppliers.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $this->getService()->store($request->all());
        return redirect()->route('admin.suppliers.index')->with('success_message', __('object_created_successfully', ['object' => __('supplier'), 'object_name' => $request->first_name." ".$request->last_name]));
    }

    public function edit($userId)
    {
        return view('admin.suppliers.update', [
            'data' => $this->getService()->getById($userId),
        ]);
    }

    public function update(Request $request, $branchId)
    {
        $this->getService()->update($branchId, $request->all());
        return redirect()->route('admin.suppliers.index')->with('success_message', __('object_updated_successfully', ['object' => __('supplier'), 'object_name' => $request->first_name." ".$request->last_name]));
    }

    public function delete(Request $request, $branchId)
    {
        $this->getService()->delete($branchId);
        return response()->json([
            'success' => true,
            'message' => __('object_deleted_successfully', ['object' => __('supplier'), 'object_name' => $request->name]),
            'code'    => 200,
        ]);
    }
}
