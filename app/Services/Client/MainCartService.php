<?php

namespace App\Services\Client;

use App\Helpers\ContactMessageHelper;
use App\Models\CartProduct;
use App\Repositories\Client\MainCartRepository;

class MainCartService
{
    public function __construct(
        protected MainCartRepository $mainCartRepository,
        protected ContactMessageHelper $contactMessageHelper
    ) {}

    public function getUserCartList(int $user_id)
    {
        return $this->mainCartRepository->getUserCartList($user_id);
    }

    public function addToCart($cart_request)
    {
        return $this->mainCartRepository->addToCart($this->prepareCartRequest($cart_request));
    }

    public function updateCartDetails(array $cart_request, CartProduct $cartProduct)
    {
        // $cart_details = $this->mainCartRepository->getCartDetails($id);

        // if(!$cart_details){
        //     return null;
        // }

        return $this->mainCartRepository->updateCartDetails($cartProduct, $cart_request);
    }

    public function deleteCartList($cart_request)
    {
        if(isset($cart_request['user_id'])){
            return $this->mainCartRepository->deleteUserCart($cart_request['user_id']);
        }
        $cart_details = $this->mainCartRepository->getCartDetails($cart_request['cart_id']);
        return $this->mainCartRepository->deleteCartDetails($cart_details);

    }

    public function prepareCartRequest(array $cart_request)
    {

        $request_data = [
            'user_id' => $cart_request['user_id'] ?? null,
            'variant_id' => $cart_request['variant_id'] ?? null,
            'quantity' => $cart_request['quantity'] ?? 1,
        ];

        return $request_data;
    }


}
