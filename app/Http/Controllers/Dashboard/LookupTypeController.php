<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\LookupType\UpdateLookupType;
use Illuminate\Http\Request;
use App\Http\Resources\LookupTypeResource;
use App\Services\Dashboard\LookupTypeService;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Dashboard\LookupType\AddLookupType;
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
        $lookup_type_list = $this->lookupTypeService->getLookupTypeList();

        return ResponseHelper::success(
            LookupTypeResource::collection($lookup_type_list),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200
        );
    }

    // GET /lookup-types/{id}
    public function show(LookupType $lookupType)
    {
        $lookup_type = $this->lookupTypeService->getLookupTypeDetails($lookupType);

        if (!$lookup_type) {
            return ResponseHelper::error(
                $lookup_type,
                [
                    'en' => trans('validation.data_not_found', [], 'en'),
                    'ar' => trans('validation.data_not_found', [], 'ar'),
                ],
                404);
        }

        return ResponseHelper::success(
            new LookupTypeResource($lookup_type),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200
        );
    }

    // POST /lookup-types
    public function store(AddLookupType $request)
    {
        try {
            $lookup_type = $this->lookupTypeService->addNewLookupType($request->validated());

            return ResponseHelper::success(
                new LookupTypeResource($lookup_type),
                [
                    'en' => trans('validation.data_added', [], 'en'),
                    'ar' => trans('validation.data_added', [], 'ar'),
                ],
                201
            );
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

    // PUT /lookup-types/{id}
    public function update(UpdateLookupType $request, LookupType $lookupType)
    {
        try {
            $lookup_type = $this->lookupTypeService->updateLookupType($request->validated(), $lookupType);

            if (!$lookup_type) {
                return ResponseHelper::error(
                    $lookup_type,
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                new LookupTypeResource($lookup_type),
                [
                    'en' => trans('validation.data_updated', [], 'en'),
                    'ar' => trans('validation.data_updated', [], 'ar'),
                ],
                201
            );
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

    // DELETE /lookup-types/{id}
    public function destroy(Request $request, LookupType $lookupType)
    {
        try {
            $lookup_type = $this->lookupTypeService->deleteLookupType($request->all(), $lookupType);

            if (!$lookup_type) {
                return ResponseHelper::error(
                    $lookup_type,
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
