<?php

namespace App\Services\Client;

use App\Helpers\ContactMessageHelper;
use App\Models\CartProduct;
use App\Repositories\Client\MainCartRepository;
use App\Services\ActingUserService;

class MainCartService
{
    public function __construct(
        protected MainCartRepository $mainCartRepository,
        protected ContactMessageHelper $contactMessageHelper,
        protected ActingUserService $actingUserService,
    ) {}

    public function getUserCartList()
    {
        return $this->mainCartRepository->getUserCartList($this->actingUserService->id());
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
        if (! isset($cart_request['cart_id'])) {
            return $this->mainCartRepository->deleteUserCart($this->actingUserService->id());
        }
        $cart_details = $this->mainCartRepository->getCartDetails($cart_request['cart_id']);

        return $this->mainCartRepository->deleteCartDetails($cart_details);

    }

    public function prepareCartRequest(array $cart_request)
    {

        $request_data = [
            'user_id' => $this->actingUserService->id(),
            'variant_id' => $cart_request['variant_id'] ?? null,
            'quantity' => $cart_request['quantity'] ?? 1,
        ];

        return $request_data;
    }
}
