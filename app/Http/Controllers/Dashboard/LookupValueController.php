<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\LookupValueService;
use App\Http\Resources\LookupValueResource;
use App\Helpers\ResponseHelper;

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

        return ResponseHelper::success(LookupValueResource::collection($lookup_value_list), "Lookup Value list Returned Successfully.", 200);

    }

    // getLookupValueDetails Funtion to Get Lookup Value Details
    public function show(int $id)
    {

        $lookup_value_details =  $this->lookupValueService->getLookupValueDetails($id);

        return ResponseHelper::success(
                new LookupValueResource($lookup_value_details),
                "Lookup Value #($id) Returned Successfully.",
                200);
    }

    // addNewLookupValue Funtion To Add New Lookup Value
    public function store(Request $request)
    {

        try {

            $get_lookup_value_details = $this->lookupValueService->addNewLookupValue($request->all());

            return ResponseHelper::success(
                    $get_lookup_value_details,
                    "Lookup Value Added Successfully",
                    201);
        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // updateLookupValue Funtion To Update Lookup Value
    public function update(Request $request, int $id)
    {
        try {

            $lookup_value_details =  $this->lookupValueService->updateLookupValue($request->all(), $id);

            return ResponseHelper::success(
                $lookup_value_details,
                "Lookup Value Updated Successfully",
                201);

        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteLookupValue Funtion To Delete Lookup Value
    public function destroy(Request $request, int $id)
    {
        try {
            $this->lookupValueService->deleteLookupValue($request->all(), $id);

            return ResponseHelper::success(
                null,
                "Lookup Value Deleted Successfully.",
                200);
        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
