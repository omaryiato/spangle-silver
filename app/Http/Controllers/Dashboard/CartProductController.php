<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartProductResource;
use App\Models\CartProduct;
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

        return ResponseHelper::success(
            CartProductResource::collection($cart_products_list),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);

    }

    // Funtion to Get Cart Product Details
    public function show(CartProduct $cartProduct)
    {

        $cart_product_details =  $this->cartProductService->getCartProductDetails($cartProduct->id);

        return ResponseHelper::success(
            new CartProductResource($cart_product_details),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);

    }
}
