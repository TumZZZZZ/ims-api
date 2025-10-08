<?php

namespace App\Http\Controllers;

use App\Services\SVStore;
use Illuminate\Http\Request;

/**
 * @group Admin
 *
 * To detail for Admin routes
 *
 * @authenticated
 * */
class StoreController extends BaseApi
{
    public function getService()
    {
        return new SVStore();
    }

    /**
     * List Stores
     *
     * @queryParam search string Search term to filter stores by name. Example: Angkor
     *
     * @authenticated
     * @responseFile storage/response/store/list-stores.json
     */
    public function index(Request $request)
    {
        try {
            $data = $this->getService()->getStores($request->all());
            return $this->sendResponse($data, 'Store list retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Retrieve store list failed', 500, ['error' => $th->getMessage()]);
        }
    }

    /**
     * Create Store
     *
     * @group Super Admin
     *
     * @bodyParam name string required The name of the store. Example: Main Store
     * @bodyParam location string required The location of the store. Example: 123 Main St, Cityville
     * @bodyParam user array required The user details for the store manager.
     * @bodyParam user.first_name string required The first name of the user. Example: John
     * @bodyParam user.last_name string required The last name of the user. Example: Doe
     * @bodyParam user.email string required The email of the user. Example: johnjoe@gmail.com
     * @bodyParam user.password string required The password of the user. Example: password123
     * @bodyParam user.confirm_password string required The password confirmation of the user. Example: password123
     * @bodyParam user.calling_code string required The calling code of the user's phone number. Example: 855
     * @bodyParam user.phone_number string required The phone number of the user. Example: 1234567890
     *
     * @authenticated
     * @responseFile storage/response/success.json
     * */
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

    /**
     * Update Store
     *
     * @bodyParam image string The image paht. Example: images/store/IAW1QBHRGuHda0KMnF3Rt9d98GcYxIxVL2Q6e5ww.jpg
     * @bodyParam name string required The name of the store. Example: Main Store
     * @bodyParam location string required The location of the store. Example: 123 Main St, Cityville
     *
     * @authenticated
     * @responseFile storage/response/success.json
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'image'    => 'nullable|string',
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        try {
            $data = $this->getService()->update($id, $request->all());
            return $this->sendResponse($data, 'Store updated successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Update store failed', 500, ['error' => $th->getMessage()]);
        }
    }
}
