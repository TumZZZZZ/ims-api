<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\BaseApi;
use App\Services\SuperAdmin\SVMerchant;
use Illuminate\Http\Request;

class MerchantController extends BaseApi
{
    public function getService()
    {
        return new SVMerchant();
    }

    public function index(Request $request)
    {
        return view('super-admin.merchants.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }

    public function create()
    {
        return view('super-admin.merchants.create');
    }

    public function store(Request $request)
    {
        $this->getService()->store($request->all());
        return redirect()->route('super-admin.merchants.index')->with('success_message', __('object_created_successfully', ['object' => __('merchant'), 'object_name' => $request->merchant_name]));
    }

    public function edit($merchantId)
    {
        return view('super-admin.merchants.update', [
            'data' => $this->getService()->getById($merchantId),
        ]);
    }

    public function update(Request $request, $merchantId)
    {
        $this->getService()->update($merchantId, $request->all());
        return redirect()->route('super-admin.merchants.index')->with('success_message', __('object_updated_successfully', ['object' => __('merchant'), 'object_name' => $request->merchant_name]));
    }

    public function delete(Request $request, $merchantId)
    {
        $this->getService()->delete($merchantId);
        return response()->json([
            'success' => true,
            'message' => __('object_deleted_successfully', ['object' => __('merchant'), 'object_name' => $request->name]),
            'code'    => 200,
        ]);
    }

    public function suspendOrActivate(Request $request, $merchantId)
    {
        $this->getService()->suspendOrActivate($merchantId, $request->all());
        return back()->with('success_message', __('object_action_successfully', ['object_name' => $request->name, 'action' => $request->action]));
    }
}
