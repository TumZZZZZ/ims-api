<?php

namespace App\Http\Controllers;

use App\Services\SVUpload;
use Illuminate\Http\Request;

/**
 * @group General
 *
 * To detail for Global routes
 *
 * @authenticated
 * */
class UploadController extends BaseApi
{
    public function getService()
    {
        return new SVUpload();
    }

    /**
     * Upload Image
     *
     * Uploads an image file.
     *
     * @bodyParam type string required The type of the image (e.g., 'profile', 'product'). Example: profile
     * @bodyParam image file required The image file to upload. Must be a jpg, jpeg, or png file and not exceed 10MB in size.
     *
     * @authenticated
     * @response 200 {
     *    "status": true,
     *    "code": 200,
     *    "message": "Image uploaded successfully",
     *    "data": {
     *      "path": "images/store/IAW1QBHRGuHda0KMnF3Rt9d98GcYxIxVL2Q6e5ww.jpg"
     *    }
     * }
     */
    public function store(Request $request)
    {
        $request->validate([
            'type'  => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120', // max 5MB, only jpg, jpeg, png
        ]);

        try {
            $path = $this->getService()->upload($request->all());
            return $this->sendResponse(['path' => $path], 'Image uploaded successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Image upload failed', 500, ['error' => $th->getMessage()]);
        }
    }
}
