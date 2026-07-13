<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\ShippingMethodService;
use App\Http\Resources\ShippingMethodResource;
use App\Helpers\ResponseHelper;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ShippingMethodController extends Controller
{

    protected $shippingMethodService;

    public function __construct(ShippingMethodService $shippingMethodService)
    {
        $this->shippingMethodService = $shippingMethodService;
    }

    // index Funtion to Get Shipping Methods List
    public function index()
    {
        $shipping_methods_list = $this->shippingMethodService->getShippingMethodsList();

        return ResponseHelper::success(
            ShippingMethodResource::collection($shipping_methods_list),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);

    }

    // show Funtion to Get Shipping Method Details
    public function show(ShippingMethod $shippingMethod)
    {

        $shipping_method_details =  $this->shippingMethodService->getShippingMethodDetails($shippingMethod->id);

        return ResponseHelper::success(
            new ShippingMethodResource($shipping_method_details),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);

    }

    // store Funtion To Add New Shipping Method
    public function store(Request $request)
    {

        try {

            $shipping_method_details = $this->shippingMethodService->addNewShippingMethod($request->all());

            return ResponseHelper::success(
                new ShippingMethodResource($shipping_method_details),
                [
                    'en' => trans('validation.data_added'),
                    'ar' => trans('validation.data_added'),
                ],
                201);

        } catch (\Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    // update Funtion To Update Shipping Method
    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        try {

            $shipping_method_details =  $this->shippingMethodService->updateShippingMethod($request->all(), $shippingMethod->id);

            if (!$shipping_method_details) {
                return ResponseHelper::error(
                    $shipping_method_details,
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                new ShippingMethodResource($shipping_method_details),
                [
                    'en' => trans('validation.data_updated'),
                    'ar' => trans('validation.data_updated'),
                ],
                201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    // destroy Funtion To Delete Shipping Method
    public function destroy(Request $request, int $id)
    {
        try {

            $shipping_method_details = $this->shippingMethodService->deleteShippingMethod($id);

            if (!$shipping_method_details) {
                return ResponseHelper::error(
                    $shipping_method_details,
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                null,
                [
                    'en' => trans('validation.data_deleted'),
                    'ar' => trans('validation.data_deleted'),
                ],
                200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }
    }

}
