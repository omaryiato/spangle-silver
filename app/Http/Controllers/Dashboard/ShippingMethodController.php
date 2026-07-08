<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\ShippingMethodService;
use App\Http\Resources\ShippingMethodResource;
use App\Helpers\ResponseHelper;
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

        return ResponseHelper::success(ShippingMethodResource::collection($shipping_methods_list), "Shipping Method Returned Successfully.", 200);

    }

    // show Funtion to Get Shipping Method Details
    public function show(int $id)
    {

        $shipping_method_details =  $this->shippingMethodService->getShippingMethodDetails($id);

        return ResponseHelper::success(new ShippingMethodResource($shipping_method_details), "Shipping Method #($id) Returned Successfully.", 200);

    }

    // store Funtion To Add New Shipping Method
    public function store(Request $request)
    {

        try {

            $shipping_method_details = $this->shippingMethodService->addNewShippingMethod($request->all());

            return ResponseHelper::success($shipping_method_details,"method added successfully",201);
        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // update Funtion To Update Shipping Method
    public function update(Request $request, int $id)
    {
        try {

            $shipping_method_details =  $this->shippingMethodService->updateShippingMethod($request->all(), $id);

            return ResponseHelper::success($shipping_method_details, "method updated successfully",201);

        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // destroy Funtion To Delete Shipping Method
    public function destroy(Request $request, int $id)
    {
        try {

            $this->shippingMethodService->deleteShippingMethod($id);
            return ResponseHelper::success(null, "method deleted successfully", 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
