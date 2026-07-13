<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\LookupTypeResource;
use App\Services\Dashboard\LookupTypeService;
use App\Helpers\ResponseHelper;
use App\Models\LookupType;

class LookupTypeController extends Controller
{
    protected $lookupTypeService;

    public function __construct(LookupTypeService $lookupTypeService)
    {
        $this->lookupTypeService = $lookupTypeService;
    }

    // GET /lookup-types
    public function index()
    {
        $lookupTypes = $this->lookupTypeService->getLookupTypeList();

        return ResponseHelper::success(
            LookupTypeResource::collection($lookupTypes),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200
        );
    }

    // GET /lookup-types/{id}
    public function show(LookupType $lookupType)
    {
        $lookup_type = $this->lookupTypeService->getLookupTypeDetails($lookupType->id);

        if (!$lookup_type) {
            return ResponseHelper::error(
                $lookup_type,
                [
                    'en' => trans('validation.data_not_found'),
                    'ar' => trans('validation.data_not_found'),
                ],
                404);
        }

        return ResponseHelper::success(
            new LookupTypeResource($lookup_type),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200
        );
    }

    // POST /lookup-types
    public function store(Request $request)
    {
        try {
            $lookup_type = $this->lookupTypeService->addNewLookupType($request->all());

            return ResponseHelper::success(
                new LookupTypeResource($lookup_type),
                [
                    'en' => trans('validation.data_added'),
                    'ar' => trans('validation.data_added'),
                ],
                201
            );
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

    // PUT /lookup-types/{id}
    public function update(Request $request, LookupType $lookupType)
    {
        try {
            $lookup_type = $this->lookupTypeService->updateLookupType($request->all(), $lookupType->id);

            if (!$lookup_type) {
                return ResponseHelper::error(
                    $lookup_type,
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                new LookupTypeResource($lookup_type),
                [
                    'en' => trans('validation.data_updated'),
                    'ar' => trans('validation.data_updated'),
                ],
                201
            );
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

    // DELETE /lookup-types/{id}
    public function destroy(Request $request, LookupType $lookupType)
    {
        try {
            $lookup_type = $this->lookupTypeService->deleteLookupType($request->all(), $lookupType->id);

            if (!$lookup_type) {
                return ResponseHelper::error(
                    $lookup_type,
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
