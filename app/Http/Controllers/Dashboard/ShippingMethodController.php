<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\ShippingMethodService;
use App\Http\Resources\ShippingMethodResource;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Dashboard\ShippingMethod\AddShippingMethod;
use App\Http\Requests\Dashboard\ShippingMethod\UpdateShippingMethod;
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
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);

    }

    // show Funtion to Get Shipping Method Details
    public function show(ShippingMethod $shippingMethod)
    {

        $shipping_method_details =  $this->shippingMethodService->getShippingMethodDetails($shippingMethod);

        return ResponseHelper::success(
            new ShippingMethodResource($shipping_method_details),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);

    }

    // store Funtion To Add New Shipping Method
    public function store(AddShippingMethod $request)
    {

        try {

            $shipping_method_details = $this->shippingMethodService->addNewShippingMethod($request->validated());

            return ResponseHelper::success(
                new ShippingMethodResource($shipping_method_details),
                [
                    'en' => trans('validation.data_added', [], 'en'),
                    'ar' => trans('validation.data_added', [], 'ar'),
                ],
                201);

        } catch (\Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    // update Funtion To Update Shipping Method
    public function update(UpdateShippingMethod $request, ShippingMethod $shippingMethod)
    {
        try {

            $shipping_method_details =  $this->shippingMethodService->updateShippingMethod($request->validated(), $shippingMethod);

            if (!$shipping_method_details) {
                return ResponseHelper::error(
                    $shipping_method_details,
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                new ShippingMethodResource($shipping_method_details),
                [
                    'en' => trans('validation.data_updated', [], 'en'),
                    'ar' => trans('validation.data_updated', [], 'ar'),
                ],
                201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    // destroy Funtion To Delete Shipping Method
    public function destroy(Request $request, ShippingMethod $shippingMethod)
    {
        try {

            $shipping_method_details = $this->shippingMethodService->deleteShippingMethod($shippingMethod);

            if (!$shipping_method_details) {
                return ResponseHelper::error(
                    $shipping_method_details,
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                null,
                [
                    'en' => trans('validation.data_deleted', [], 'en'),
                    'ar' => trans('validation.data_deleted', [], 'ar'),
                ],
                200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

}
