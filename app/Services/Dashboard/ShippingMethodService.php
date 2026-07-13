<?php

namespace App\Services\Dashboard;

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
    public function getShippingMethodDetails(int $id)
    {
        return $this->shippingMethodRepository->getShippingMethodDetails($id);
    }

    // addNewShippingMethod Funtion To Add new Shipping Method
    public function addNewShippingMethod(array $shipping_method_request)
    {

        try {
            return $this->shippingMethodRepository->addNewShippingMethod(
                $this->prepareRequestInfo($shipping_method_request));
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateShippingMethod Funtion To Update Shipping Method info
    public function updateShippingMethod(array $shipping_method_request, int $id)
    {

        try {
            $shipping_method_details = $this->shippingMethodRepository->getShippingMethodDetails($id);
            if(!$shipping_method_details){
                return null;
            }
            return $this->shippingMethodRepository->updateShippingMethod($shipping_method_details, $this->prepareRequestInfo($shipping_method_request));

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteShippingMethod Funtion To Delete Shipping Method
    public function deleteShippingMethod(int $id)
    {
        try {
            $shipping_method_details = $this->shippingMethodRepository->getShippingMethodDetails($id);
            if(!$shipping_method_details){
                return null;
            }
            return $this->shippingMethodRepository->deleteShippingMethod($shipping_method_details);
        } catch (\Exception $exception) {
            throw $exception;
        }
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

