<?php

namespace App\Http\Controllers\API;

use App\Services\API\SVSaleScreen;
use Illuminate\Http\Request;

/**
 * @group Sale Screen
 *
 * To detail for authorization to protect route
 *
 * @authenticated
 * */
class SaleScreenController extends BaseApi
{
    public function getService()
    {
        return new SVSaleScreen();
    }

    /**
     * Get Payment Methods
     *
     * @responseFile storage/response/sale-screen/get-payment-methods.json
     */
    public function getPaymentMethods()
    {
        try {
            $data = $this->getService()->getPaymentMethods();
            return $this->responseSuccess($data);
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }

    /**
     * Get All Categories
     *
     * @queryParam search string optional
     *
     * @responseFile storage/response/sale-screen/get-all-categories.json
     */
    public function getAllCategories(Request $request)
    {
        try {
            $data = $this->getService()->getAllCategories($request->all());
            return $this->responseSuccess($data);
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }

    /**
     * Get Product by Category
     *
     * @queryParam search string optional
     *
     * @urlParam category_id string required The category id.
     *
     * @responseFile storage/response/sale-screen/get-product-by-category.json
     */
    public function getProductByCategory(Request $request, $categoryId)
    {
        try {
            $data = $this->getService()->getProductByCategory($categoryId, $request->all());
            return $this->responseSuccess($data);
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }

    /**
     * Get Order Details
     *
     * @responseFile storage/response/sale-screen/get-order-details.json
     */
    public function getOrderDetails()
    {
        try {
            $data = $this->getService()->getOrderDetails();
            return $this->responseSuccess($data);
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }

    /**
     * Add Order
     *
     * @bodyParam category_id string required The category ID. Example: 694585e85ddc7807260a4839
     * @bodyParam product_id string required The product ID. Example: 694585e85ddc7807260a4839
     * @bodyParam quantity integer required The quantity of the product. Example: 1
     * @bodyParam discount_id string optional The discount ID. Can be empty.
     *
     * @responseFile storage/response/sale-screen/get-order-details.json
     */
    public function addOrder(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required|string',
                'product_id' => 'required|string',
                'quantity'  => 'required|integer',
            ]);
            $data = $this->getService()->addOrder($request->all());
            return $this->responseSuccess($data);
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }

    /**
     * Adjust Order Quantity
     *
     * @urlParam order_id string required The order id.
     *
     * @bodyParam product_id string required The product ID. Example: 694585e85ddc7807260a4839
     * @bodyParam quantity integer required The quantity of the product. Example: 1
     * @bodyParam action string required The action of adjustment. Example: add
     *
     * @responseFile storage/response/sale-screen/get-order-details.json
     */
    public function adjustOrderQuantity(Request $request, $orderId)
    {
        try {
            $request->validate([
                'product_id' => 'required|string',
                'quantity'  => 'required|integer',
                'action' => 'required|string|in:add,remvoe'
            ]);
            $data = $this->getService()->adjustOrderQuantity($orderId, $request->all());
            return $this->responseSuccess($data);
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }

    /**
     * Remove Product form Order
     *
     * @urlParam order_id string required The order id.
     *
     * @bodyParam product_id string required The product ID. Example: 694585e85ddc7807260a4839
     *
     * @responseFile storage/response/sale-screen/get-order-details.json
     */
    public function removeProductFromOrder(Request $request, $orderId)
    {
        try {
            $request->validate([
                'product_id' => 'required|string',
            ]);
            $data = $this->getService()->removeProductFromOrder($orderId, $request->all());
            return $this->responseSuccess($data);
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }

    /**
     * Remove Order
     *
     * @urlParam order_id string required The order id. Example: 694585e85ddc7807260a4839
     *
     * @responseFile storage/response/success.json
     */
    public function removeOrder($orderId)
    {
        try {
            $data = $this->getService()->removeOrder($orderId);
            return $this->responseSuccess($data);
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }

    /**
     * Place Order
     *
     * @bodyParam order_id string required The order ID. Example: 694585e85ddc7807260a4839
     * @bodyParam payment_id string required The payment ID. Example: 694585e85ddc7807260a4839
     *
     * @responseFile storage/response/success.json
     */
    public function placeOrder(Request $request)
    {
        try {
            $data = $this->getService()->placeOrder($request->all());
            return $this->responseSuccess($data);
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }
}
