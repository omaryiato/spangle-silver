<?php

namespace App\Repositories\Client;

use App\Models\OrderDetail;

class MainOrderRepository
{

    public function placeNewOrder($order_request)
    {
        return OrderDetail::create($order_request);
    }

}
