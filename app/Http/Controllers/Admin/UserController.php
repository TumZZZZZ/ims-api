<?php

namespace App\Http\Controllers\Admin;

use App\Enum\Constants;
use App\Http\Controllers\Controller;
use App\Services\Admin\SVUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function getService()
    {
        return (new SVUser());
    }

    public function index(Request $request)
    {
        return view('admin.users.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'roles' => getRoles(),
            'branches' => Auth::user()->getBranches(),
        ]);
    }

    public function store(Request $request)
    {
        if ($request->role === Constants::ROLE_STAFF && count($request->branch_ids) >= 2) {
            return redirect()->route('admin.users.index')->with('error_message', 'Staff must select exactly one branch');
        }
        $this->getService()->store($request->all());
        return redirect()->route('admin.users.index')->with('success_message', __('object_created_successfully', ['object' => __('user'), 'object_name' => $request->first_name." ".$request->last_name]));
    }

    public function edit($userId)
    {
        return view('admin.users.update', [
            'data' => $this->getService()->getById($userId),
            'roles' => getRoles(),
            'branches' => Auth::user()->getBranches(),
        ]);
    }

    public function update(Request $request, $branchId)
    {
        $this->getService()->update($branchId, $request->all());
        return redirect()->route('admin.users.index')->with('success_message', __('object_updated_successfully', ['object' => __('user'), 'object_name' => $request->first_name." ".$request->last_name]));
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
}
