<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ShippingMethod\AddShippingMethod;
use App\Http\Requests\Dashboard\ShippingMethod\UpdateShippingMethod;
use App\Http\Resources\ShippingMethodResource;
use App\Models\ShippingMethod;
use App\Services\Dashboard\ShippingMethodService;
use Illuminate\Http\Request;

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

        $shipping_method_details = $this->shippingMethodService->getShippingMethodDetails($shippingMethod);

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

        $shipping_method_details = $this->shippingMethodService->addNewShippingMethod($request->validated());

        return ResponseHelper::success(
            new ShippingMethodResource($shipping_method_details),
            [
                'en' => trans('validation.data_added', [], 'en'),
                'ar' => trans('validation.data_added', [], 'ar'),
            ],
            201);

    }

    // update Funtion To Update Shipping Method
    public function update(UpdateShippingMethod $request, ShippingMethod $shippingMethod)
    {

        $shipping_method_details = $this->shippingMethodService->updateShippingMethod($request->validated(), $shippingMethod);

        if (! $shipping_method_details) {
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
            200);

    }

    // destroy Funtion To Delete Shipping Method
    public function destroy(Request $request, ShippingMethod $shippingMethod)
    {

        $shipping_method_details = $this->shippingMethodService->deleteShippingMethod($shippingMethod);

        if (! $shipping_method_details) {
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

    }
}
