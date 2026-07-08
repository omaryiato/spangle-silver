<?php

namespace App\Http\Controllers\Client;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartProductResource;
use App\Models\CartProduct;
use App\Services\Client\MainCartService;
use Exception;
use Illuminate\Http\Request;

class MainCartController extends Controller
{
    public function __construct(
        protected MainCartService $mainCartService
    ) {}

    public function getUserCartList(int $user_id)
    {
        $user_cart = $this->mainCartService->getUserCartList($user_id);

        return ResponseHelper::success(
            CartProductResource::collection($user_cart),
            [
                'en' => trans('validation.home_page'),
                'ar' => trans('validation.home_page'),
            ],
            200
        );
    }

    public function addToCartList(Request $request)
    {
        try{

            $cart_details = $this->mainCartService->addToCartList($request);

            return ResponseHelper::success(
                CartProductResource::collection($cart_details),
                [
                    'en' => trans('validation.home_page'),
                    'ar' => trans('validation.home_page'),
                ],
                200
            );
        } catch(Exception $exception){

        }
    }

    public function updateUserCartDetails(Request $request, int $id)
    {
        try{

            $cart_details = $this->mainCartService->updateUserCartDetails($request, $id);

            return ResponseHelper::success(
                CartProductResource::collection($cart_details),
                [
                    'en' => trans('validation.home_page'),
                    'ar' => trans('validation.home_page'),
                ],
                200
            );
        } catch(Exception $exception){

        }
    }

    public function deleteCartList(Request $request)
    {
        try{

            $cart_details = $this->mainCartService->deleteCartList($request);

            return ResponseHelper::success(
                CartProduct::collection($cart_details),
                [
                    'en' => trans('validation.home_page'),
                    'ar' => trans('validation.home_page'),
                ],
                200
            );
        } catch(Exception $exception){

        }
    }


}
