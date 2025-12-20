<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SVCategory;
use App\Services\Admin\SVProduct;
use App\Services\Admin\SVPromotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function getService()
    {
        return (new SVPromotion());
    }

    public function index(Request $request)
    {
        return view('admin.promotions.index', [
            'data' => $this->getService()->getWithPagination($request->all()),
        ]);
    }

    public function create()
    {
        return view('admin.promotions.create', [
            'promotion_types' => $this->getService()->getPromotionTypes(),
            'products' => (new SVProduct)->getAllProducts(),
            'categories' => (new SVCategory)->getAllCategories(),
        ]);
    }

    public function store(Request $request)
    {
        $this->getService()->store($request->all());
        return redirect()->route('admin.promotions.index')->with('success_message', __('object_created_successfully', ['object' => __('promotion'), 'object_name' => $request->name]));
    }

    public function edit($promotionId)
    {
        return view('admin.promotions.update', [
            'data' => $this->getService()->getById($promotionId),
            'promotion_types' => $this->getService()->getPromotionTypes(),
            'products' => (new SVProduct)->getAllProducts(),
            'categories' => (new SVCategory)->getAllCategories(),
        ]);
    }

    public function update(Request $request, $promotionId)
    {
        $this->getService()->update($promotionId, $request->all());
        return redirect()->route('admin.promotions.index')->with('success_message', __('object_updated_successfully', ['object' => __('promotion'), 'object_name' => $request->name]));
    }

    public function delete(Request $request, $promotionId)
    {
        $this->getService()->delete($promotionId);
        return response()->json([
            'success' => true,
            'message' => __('object_deleted_successfully', ['object' => __('promotion'), 'object_name' => $request->name]),
            'code'    => 200,
        ]);
    }
}
