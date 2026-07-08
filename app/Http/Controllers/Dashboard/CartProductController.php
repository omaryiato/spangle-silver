<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartProductResource;
use Illuminate\Http\Request;
use App\Services\Dashboard\CartProductService;

class CartProductController extends Controller
{
    protected $cartProductService;

    public function __construct(CartProductService $cartProductService)
    {
        $this->cartProductService = $cartProductService;
    }

    // Funtion to Get Cart Product List
    public function index()
    {

        $cart_products_list = $this->cartProductService->getCartProductsList();

        return ResponseHelper::success(CartProductResource::collection($cart_products_list), "CartProducts Returned Successfully.", 200);

    }

    // Funtion to Get Cart Product Details
    public function show(int $id)
    {

        $cart_product_details =  $this->cartProductService->getCartProductDetails($id);

        return ResponseHelper::success(new CartProductResource($cart_product_details), "Address #($id) Returned Successfully.", 200);

    }

    // Funtion To Add New Cart Product
    public function store(Request $request)
    {

        try {

            $cart_product_details = $this->cartProductService->addNewCartProduct($request->all());

            return ResponseHelper::success($cart_product_details,"User Address Add Successfully.",201);
        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // Funtion To Update Cart Product
    public function update(Request $request, int $id)
    {
        try {

            $cart_product_details =  $this->cartProductService->updateCartProduct($request->all(), $id);

            return ResponseHelper::success($cart_product_details,"User Address Updated Successfully.",201);

        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // Funtion To Delete Cart Product
    public function destroy(Request $request, int $id)
    {
        try {

            $this->cartProductService->deleteCartProduct($request->all(), $id);

            return ResponseHelper::success(null, "User Address Deleted Successfully.", 201);
        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }
}
