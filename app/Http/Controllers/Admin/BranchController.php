<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApi;
use App\Services\Admin\SVBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends BaseApi
{
    public function getService()
    {
        return (new SVBranch());
    }

    public function index(Request $request)
    {
        return view('admin.branches.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }

    public function create()
    {
        return view('admin.branches.create', [
            'currencies' => getAvailableCurrencies(),
        ]);
    }

    public function store(Request $request)
    {
        $this->getService()->store($request->all());
        return redirect()->route('admin.branches.index')->with('success_message', __('object_created_successfully', ['object' => __('branch'), 'object_name' => $request->name]));
    }

    public function edit($userId)
    {
        return view('admin.branches.update', [
            'data' => $this->getService()->getById($userId),
            'currencies' => getAvailableCurrencies(),
        ]);
    }

    public function update(Request $request, $branchId)
    {
        $this->getService()->update($branchId, $request->all());
        return redirect()->route('admin.branches.index')->with('success_message', __('object_updated_successfully', ['object' => __('branch'), 'object_name' => $request->name]));
    }

    public function delete(Request $request, $branchId)
    {
        $this->getService()->delete($branchId);
        return response()->json([
            'success' => true,
            'message' => __('object_deleted_successfully', ['object' => __('branch'), 'object_name' => $request->name]),
            'code'    => 200,
        ]);
    }

    public function closeOrOpen(Request $request, $branchId)
    {
        $this->getService()->closeOrOpen($branchId, $request->all());
        return back()->with('success_message', __('object_action_successfully', ['object_name' => $request->name, 'action' => $request->action]));
    }
}
