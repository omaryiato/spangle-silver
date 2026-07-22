<?php

namespace App\Repositories\Dashboard;

use App\Models\ShippingMethod;



class ShippingMethodRepository
{

    // getShippingMethodsList Funtion To Get Shipping Methods List
    public function getShippingMethodsList()
    {
        return ShippingMethod::with(['orders'])->get();
    }

    // getShippingMethodDetails Funtion To Get Shipping Method Details
    public function getShippingMethodDetails(ShippingMethod $shippingMethod)
    {
        return  $shippingMethod;
    }

    // addNewShippingMethod Funtion To Add new Shipping Method
    public function addNewShippingMethod(array $shipping_method_request)
    {
        return ShippingMethod::create($shipping_method_request);
    }

    // updateShippingMethod Funtion To Update Shipping Method info
    public function updateShippingMethod(ShippingMethod $shipping_method, array $shipping_method_request)
    {
        $shipping_method->update($shipping_method_request);
        return $shipping_method;
    }

    // deleteShippingMethod Funtion To Delete Shipping Method
    public function deleteShippingMethod(ShippingMethod $shipping_method)
    {
        $shipping_method->delete();
        return $shipping_method;
    }
}

