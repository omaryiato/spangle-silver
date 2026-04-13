<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\ShippingMethodRepository;
use App\Repositories\MainControlPanelRepository;
use App\Services\ControlPanelFeatureService;


class ShippingMethodService
{

    protected $shippingMethodRepository;
    protected $controlPanelFeatureService;
    protected $mainControlPanelRepository;

    public function __construct(
                    ShippingMethodRepository $shippingMethodRepository,
                    ControlPanelFeatureservice $controlPanelFeatureService,
                    MainControlPanelRepository $mainControlPanelRepository)
    {
        $this->shippingMethodRepository = $shippingMethodRepository;
        $this->controlPanelFeatureService = $controlPanelFeatureService;
        $this->mainControlPanelRepository = $mainControlPanelRepository;
    }

    // getShippingMethodsList Funtion To Get Shipping Methods List
    public function getShippingMethodsList()
    {
        try {
            return  $this->shippingMethodRepository->getShippingMethodsList();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // getShippingMethodDetails Funtion To Get Shipping Method Details
    public function getShippingMethodDetails($shipping_method_id)
    {

        try {

            $shipping_method_details =  $this->shippingMethodRepository->getShippingMethodDetails($shipping_method_id);

            return $shipping_method_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // addNewShippingMethod Funtion To Add new Shipping Method
    public function addNewShippingMethod($shipping_method_details)
    {

        try {

            $shipping_method_id = $this->shippingMethodRepository->addNewShippingMethod($shipping_method_details);
            $get_shipping_method_details = $this->shippingMethodRepository->getShippingMethodDetails($shipping_method_id);

            return $get_shipping_method_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateShippingMethod Funtion To Update Shipping Method info
    public function updateShippingMethod($shipping_method_details)
    {

        try {
            $this->shippingMethodRepository->updateShippingMethod($shipping_method_details);
            $get_shipping_method_details = $this->shippingMethodRepository->getShippingMethodDetails($shipping_method_details['shipping_method_id']);

            return $get_shipping_method_details;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteShippingMethod Funtion To Delete Shipping Method
    public function deleteShippingMethod($shipping_method_id, $login_user)
    {
        try {

            return $this->shippingMethodRepository->deleteShippingMethod($shipping_method_id, $login_user);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

