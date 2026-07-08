<?php

namespace App\Http\Controllers\Client;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailResource;
use App\Services\Client\MainOrderService;
use Exception;
use Illuminate\Http\Request;

class MainOrderController extends Controller
{
    public function __construct(
        protected MainOrderService $mainOrderService
    ) {}

    public function placeNewOrder(Request $request)
    {
        try{

            $order_details = $this->mainOrderService->placeNewOrder($request);

            return ResponseHelper::success(
                OrderDetailResource::collection($order_details),
                [
                    'en' => trans('validation.home_page'),
                    'ar' => trans('validation.home_page'),
                ],
                200
            );
        } catch(Exception $exception){

        }
    }



}
