<?php

namespace App\Http\Controllers\Client;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\AddToCart;
use App\Http\Requests\Client\DeleteCartList;
use App\Http\Requests\Client\UpdateCartDetails;
use App\Http\Resources\CartProductResource;
use App\Models\CartProduct;
use App\Models\User;
use App\Services\Client\MainCartService;
use Exception;

class MainCartController extends Controller
{
    public function __construct(
        protected MainCartService $mainCartService
    ) {}

    public function getUserCartList(User $user)
    {
        $user_cart = $this->mainCartService->getUserCartList($user->id);

        return ResponseHelper::success(
            CartProductResource::collection($user_cart),
            [
                'en' => trans('validation.get_user_cart_list', [], 'en'),
                'ar' => trans('validation.get_user_cart_list', [], 'ar'),
            ],
            200
        );
    }

    public function addToCart(AddToCart $request)
    {
        try{

            $cart_details = $this->mainCartService->addToCart($request->validated());

            return ResponseHelper::success(
                new CartProductResource($cart_details),
                [
                    'en' => trans('validation.add_to_cart', [], 'en'),
                    'ar' => trans('validation.add_to_cart', [], 'ar'),
                ],
                200
            );
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);

        }
    }

    public function updateCartDetails(UpdateCartDetails $request, CartProduct $cartProduct)
    {
        try{

            $cart_details = $this->mainCartService->updateCartDetails($request->validated(), $cartProduct->id);

            if (!$cart_details) {
                return ResponseHelper::error(
                    $cart_details,
                    [
                        'en' => trans('validation.cart_not_found', [], 'en'),
                        'ar' => trans('validation.cart_not_found', [], 'ar'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                new CartProductResource($cart_details),
                [
                    'en' => trans('validation.update_cart_details', [], 'en'),
                    'ar' => trans('validation.update_cart_details', [], 'ar'),
                ],
                200
            );
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);

        }
    }

    public function deleteCartList(DeleteCartList $request)
    {
        try{

            $this->mainCartService->deleteCartList($request->validated());

            return ResponseHelper::success(
                null,
                [
                    'en' => trans('validation.delete_cart_list', [], 'en'),
                    'ar' => trans('validation.delete_cart_list', [], 'ar'),
                ],
                200
            );
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);

        }
    }


}
