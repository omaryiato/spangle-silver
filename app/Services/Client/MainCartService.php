<?php

namespace App\Services\Client;

use App\Helpers\ContactMessageHelper;
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
        return $this->mainCartRepository->addToCart($cart_request);
    }

    public function updateCartDetails($cart_request, int $id)
    {
        $cart_details = $this->mainCartRepository->getCartDetails($id);

        if(!$cart_details){
            return null;
        }
        
        return $this->mainCartRepository->updateCartDetails($cart_details, $cart_request);
    }

    public function deleteCartList($cart_request)
    {
        if(isset($cart_request['user_id'])){
            return $this->mainCartRepository->deleteUserCart($cart_request['user_id']);
        }
        $cart_details = $this->mainCartRepository->getCartDetails($cart_request['cart_id']);
        return $this->mainCartRepository->deleteCartDetails($cart_details);

    }


}
