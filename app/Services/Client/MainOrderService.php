<?php

namespace App\Services\Client;

use App\Helpers\ContactMessageHelper;
use App\Repositories\Client\MainOrderRepository;

class MainOrderService
{
    public function __construct(
        protected MainOrderRepository $mainOrderRepository,
        protected ContactMessageHelper $contactMessageHelper
    ) {}

    public function placeNewOrder($order_request)
    {
        return $this->mainOrderRepository->placeNewOrder($order_request);

    }

}
