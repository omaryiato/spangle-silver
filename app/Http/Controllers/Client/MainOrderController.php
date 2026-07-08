<?php

namespace App\Http\Controllers\Client;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Services\Client\MainOrderService;
use Exception;
use Illuminate\Http\Request;

class MainOrderController extends Controller
{
    public function __construct(
        protected MainOrderService $mainOrderService
    ) {}

    public function addNewOrder(Request $request)
    {
        try{

            $order_details = $this->mainOrderService->addNewOrder($request->all());

            return ResponseHelper::success(
                new OrderResource($order_details),
                [
                    'en' => trans('validation.home_page'),
                    'ar' => trans('validation.home_page'),
                ],
                200
            );
        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => __('validation.exception_error'),
                    'ar' => __('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);

        }
    }



}
