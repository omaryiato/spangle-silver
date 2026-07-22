<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\LookupValueService;
use App\Http\Resources\LookupValueResource;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Dashboard\LookupValue\AddLookupValue;
use App\Http\Requests\Dashboard\LookupValue\UpdateLookupValue;
use App\Models\LookupValue;

class LookupValueController extends Controller
{

    protected $lookupValueService;

    public function __construct(LookupValueService $lookupValueService)
    {
        $this->lookupValueService = $lookupValueService;
    }

    // getLookupValueList Funtion to Get Lookup Value List
    public function index()
    {

        $lookup_value_list = $this->lookupValueService->getLookupValueList();

        return ResponseHelper::success(
            LookupValueResource::collection($lookup_value_list),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);

    }

    // getLookupValueDetails Funtion to Get Lookup Value Details
    public function show(LookupValue $lookupValue)
    {

        $lookup_value_details =  $this->lookupValueService->getLookupValueDetails($lookupValue);

        return ResponseHelper::success(
                new LookupValueResource($lookup_value_details),
                [
                    'en' => trans('validation.data_retrieved', [], 'en'),
                    'ar' => trans('validation.data_retrieved', [], 'ar'),
                ],
                200);
    }

    // addNewLookupValue Funtion To Add New Lookup Value
    public function store(AddLookupValue $request)
    {

        try {

            $lookup_value_details = $this->lookupValueService->addNewLookupValue($request->validated());

            return ResponseHelper::success(
                    new LookupValueResource($lookup_value_details),
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

    // updateLookupValue Funtion To Update Lookup Value
    public function update(UpdateLookupValue $request, LookupValue $lookupValue)
    {
        try {

            $lookup_value_details =  $this->lookupValueService->updateLookupValue($request->validated(), $lookupValue);

            if (!$lookup_value_details) {
                return ResponseHelper::error(
                    $lookup_value_details,
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                new LookupValueResource($lookup_value_details),
                [
                    'en' => trans('validation.data_updated', [], 'en'),
                    'ar' => trans('validation.data_updated', [], 'ar'),
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

    // deleteLookupValue Funtion To Delete Lookup Value
    public function destroy(Request $request, LookupValue $lookupValue)
    {
        try {
            $lookup_value_details = $this->lookupValueService->deleteLookupValue($request->all(), $lookupValue);

            if (!$lookup_value_details) {
                return ResponseHelper::error(
                    $lookup_value_details,
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
