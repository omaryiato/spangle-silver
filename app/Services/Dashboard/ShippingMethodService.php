<?php

namespace App\Services\Dashboard;

use App\Models\ShippingMethod;
use App\Repositories\Dashboard\ShippingMethodRepository;

class ShippingMethodService
{

    protected $shippingMethodRepository;

    public function __construct(ShippingMethodRepository $shippingMethodRepository)
    {
        $this->shippingMethodRepository = $shippingMethodRepository;
    }

    // getShippingMethodsList Funtion To Get Shipping Methods List
    public function getShippingMethodsList()
    {
        return  $this->shippingMethodRepository->getShippingMethodsList();
    }

    // getShippingMethodDetails Funtion To Get Shipping Method Details
    public function getShippingMethodDetails(ShippingMethod $shippingMethod)
    {
        return $this->shippingMethodRepository->getShippingMethodDetails($shippingMethod);
    }

    // addNewShippingMethod Funtion To Add new Shipping Method
    public function addNewShippingMethod(array $shipping_method_request)
    {
        return $this->shippingMethodRepository->addNewShippingMethod(
            $this->prepareRequestInfo($shipping_method_request));
    }

    // updateShippingMethod Funtion To Update Shipping Method info
    public function updateShippingMethod(array $shipping_method_request, ShippingMethod $shippingMethod)
    {

        // $shipping_method_details = $this->shippingMethodRepository->getShippingMethodDetails($id);
        // if(!$shipping_method_details){
        //     return null;
        // }
        return $this->shippingMethodRepository->updateShippingMethod($shippingMethod, $this->prepareRequestInfo($shipping_method_request));
    }

    // deleteShippingMethod Funtion To Delete Shipping Method
    public function deleteShippingMethod(ShippingMethod $shippingMethod)
    {

        // $shipping_method_details = $this->shippingMethodRepository->getShippingMethodDetails($shippingMethod);
        // if(!$shipping_method_details){
        //     return null;
        // }
        return $this->shippingMethodRepository->deleteShippingMethod($shippingMethod);

    }

    public function prepareRequestInfo(array $request_info)
    {

        $request_data = [
            'method_en_name' => $request_info['method_en_name'] ?? null,
            'method_ar_name' => $request_info['method_ar_name'] ?? null,
            'price' => $request_info['price'] ?? null,
            'estimated_days' => $request_info['estimated_days'] ?? null,
            'status' => $request_info['status'] ?? null,
        ];

        return $request_data;
    }

}

