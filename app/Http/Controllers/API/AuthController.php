<?php

namespace App\Http\Controllers\API;

use App\Services\API\SVAuth;
use Illuminate\Http\Request;

/**
 * @group Authentication
 *
 * To detail for authorization to protect route
 *
 * @authenticated
 * */
class AuthController extends BaseApi
{
    public function getService()
    {
        return new SVAuth();
    }

    /**
     * Login
     *
     * @bodyParam email string required The email of the user. Example: testing@gmail.com
     * @bodyParam password string required The password of the user. Example: 1234567890
     *
     * @responseFile storage/response/auth/login.json
     */
    public function login(Request $request)
    {

        try {
            $request->validate([
                'email'     => 'required|email',
                'password'  => 'required',
            ]);
            $data = $this->getService()->login($request->all());
            return $this->responseSuccess($data, 'Login successful');
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }

    /**
     * Logout
     *
     * @authenticated
     * @responseFile storage/response/auth/logout.json
     */
    public function logout(Request $request)
    {
        try {
            $data = $this->getService()->logout();
            return $this->responseSuccess($data);
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }
}
