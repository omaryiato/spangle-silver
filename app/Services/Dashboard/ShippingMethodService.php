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
            return $this->shippingMethodRepository->addNewShippingMethod($shipping_method_request);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateShippingMethod Funtion To Update Shipping Method info
    public function updateShippingMethod(array $shipping_method_request, int $id)
    {

        try {
            $shipping_method_details = $this->shippingMethodRepository->getShippingMethodDetails($id);
            return $this->shippingMethodRepository->updateShippingMethod($shipping_method_details, $shipping_method_request);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteShippingMethod Funtion To Delete Shipping Method
    public function deleteShippingMethod(int $id)
    {
        try {
            $shipping_method_details = $this->shippingMethodRepository->getShippingMethodDetails($id);
            return $this->shippingMethodRepository->deleteShippingMethod($shipping_method_details);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

