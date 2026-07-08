<?php

namespace App\Repositories\Dashboard;

use App\Models\ShippingMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class ShippingMethodRepository
{

    // getShippingMethodsList Funtion To Get Shipping Methods List
    public function getShippingMethodsList()
    {
        return ShippingMethod::all();
    }

    // getShippingMethodDetails Funtion To Get Shipping Method Details
    public function getShippingMethodDetails(int $id)
    {
        return  ShippingMethod::findOrFail($id);
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

