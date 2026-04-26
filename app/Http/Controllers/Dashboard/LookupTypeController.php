<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Dashboard\LookupTypeResource;
use App\Services\Dashboard\LookupTypeService;
use App\Helpers\ResponsHelper;

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

        return ResponsHelper::success(
            LookupTypeResource::collection($lookupTypes),
            "Lookup Type Returned Successfully.",
            200
        );
    }

    // GET /lookup-types/{id}
    public function show($id)
    {
        $lookupType = $this->lookupTypeService->getLookupTypeDetails($id);

        return ResponsHelper::success(
            new LookupTypeResource($lookupType),
            "Lookup Type Returned Successfully.",
            200
        );
    }

    // POST /lookup-types
    public function store(Request $request)
    {
        try {
            $lookupType = $this->lookupTypeService->addNewLookupType($request->all());

            return ResponsHelper::success(
                new LookupTypeResource($lookupType),
                "Lookup Type Added Successfully.",
                201
            );
        } catch (\Exception $e) {
            return ResponsHelper::error($request->all(), $e->getMessage(), 400);
        }
    }

    // PUT /lookup-types/{id}
    public function update(Request $request, $id)
    {
        try {
            $lookupType = $this->lookupTypeService->updateLookupType($request->all(), $id);

            return ResponsHelper::success(
                new LookupTypeResource($lookupType),
                "Lookup Type Updated Successfully.",
                200
            );
        } catch (\Exception $e) {
            return ResponsHelper::error($request->all(), $e->getMessage(), 400);
        }
    }

    // DELETE /lookup-types/{id}
    public function destroy(Request $request, $id)
    {
        try {
            $this->lookupTypeService->deleteLookupType($request->all(), $id);

            return ResponsHelper::success(null, "Lookup Type Deleted Successfully", 200);
        } catch (\Exception $e) {
            return ResponsHelper::error($request->all(), $e->getMessage(), 400);
        }
    }
}
