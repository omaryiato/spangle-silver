<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use App\Models\CartProduct;


class CartProductRepository
{

    // getCartProductsList Funtion To Get CartProducts List
    public function getCartProductsList()
    {
        return CartProduct::with([
            'user',
            'variant.product',
            'variant.color',
            'variant.size',
            'variant.product.images',
            'variant.product.material',
            'variant.product.stone'
        ])->get();
    }

    // getCartProductDetails Funtion To Get CartProduct Details
    public function getCartProductDetails(int $id)
    {
        return CartProduct::with([
            'user',
            'variant.product',
            'variant.color',
            'variant.size',
            'variant.product.images',
            'variant.product.material',
            'variant.product.stone'
        ])->findorfail($id);
    }
}

