<?php

namespace App\Http\Controllers;

use App\Services\SVStore;
use Illuminate\Http\Request;

class StoreController extends BaseApi
{
    public function getService()
    {
        return new SVStore();
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'location'              => 'required|string|max:255',
            'user'                  => 'required|array',
            'user.first_name'       => 'required|string|max:255',
            'user.last_name'        => 'required|string|max:255',
            'user.email'            => 'required|email|unique:users,email',
            'user.password'         => 'required|string|min:8',
            'user.confirm_password' => 'required|string|same:user.password',
            'user.calling_code'     => 'required|string|max:10',
            'user.phone_number'     => 'required|string|max:20|unique:users,phone_number',
        ]);

        try {
            $data = $this->getService()->create($request->all());
            return $this->sendResponse($data, 'Store created successfully', 201);
        } catch (\Throwable $th) {
            return $this->sendError('Create store failed', 500, ['error' => $th->getMessage()]);
        }
    }
}
