<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Services\SVProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends BaseApi
{
    public function getService()
    {
        return new SVProduct();
    }

    public function index(Request $request)
    {
        try {
            $data = $this->getService()->getProductWithPagination($request->all());
            return $this->sendResponse($data, 'Product retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'                => 'required|string|max:191',
                'barcode'             => 'nullable|string|max:191|unique:products,barcode',
                'description'         => 'nullable|string',
                'unit'                => 'required|string|max:50',
                'assigns'             => 'required|array|min:1',
                'assigns.*.store_id'  => 'required|string|exists:stores,_id',
                'assigns.*.quantity'  => 'required|integer|min:0',
                'assigns.*.threshold' => 'required|integer|min:0',
                'assigns.*.price'     => 'required|numeric|min:0',
                'assigns.*.cost'      => 'required|numeric|min:0',
            ]);
            $data = $this->getService()->create($request->all());
            return $this->sendResponse($data, 'Product created successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data = $this->getService()->getById($id);
            return $this->sendResponse($data, 'Product retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name'                => 'required|string|max:191',
                'barcode'             => 'nullable|string|max:191|unique:products,barcode,' . $id . ',_id',
                'description'         => 'nullable|string',
                'unit'                => 'required|string|max:50',
                'assigns'             => 'required|array|min:1',
                'assigns.*.store_id'  => 'required|string|exists:stores,_id',
                'assigns.*.quantity'  => 'required|integer|min:0',
                'assigns.*.threshold' => 'required|integer|min:0',
                'assigns.*.price'     => 'required|numeric|min:0',
                'assigns.*.cost'      => 'required|numeric|min:0',
            ]);
            $data = $this->getService()->update($id, $request->all());
            return $this->sendResponse($data, 'Product updated successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->getService()->delete($id);
            return $this->sendResponse([], 'Product deleted successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $filePath = $this->getService()->export($request->all());
            return $this->sendResponse(['file_url' => url($filePath)], 'Product exported successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:csv,txt',
            ]);

            $file = $request->file('file');
            $data = $this->getService()->import($file->getRealPath());

            return $this->sendResponse($data, 'Product imported successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
