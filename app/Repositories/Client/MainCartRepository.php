<?php

namespace App\Repositories\Client;

use App\Models\CartProduct;

class MainCartRepository
{

    public function getCartDetails(int $id)
    {
        return CartProduct::findOrFail($id);
    }
    public function getUserCartList(int $user_id)
    {
        return CartProduct::where('user_id', $user_id)->get();
    }

    public function addToCartList($cart_request)
    {
        return CartProduct::create($cart_request);
    }

    public function updateUserCartDetails(CartProduct $cart_details, $cart_request)
    {
        $cart_details->update($cart_request);
        return $cart_details;
    }

    public function deleteCartDetails(CartProduct $cart_details)
    {
        $cart_details->delete();
        return $cart_details;
    }

    public function deleteUserCart(int $user_id)
    {
        return CartProduct::where('user_id', $user_id)->delete();
    }

}
