<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\AddressService;
use App\Http\Resources\AddressResource;
use App\Helpers\ResponseHelper;


class AddressController extends Controller
{

    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    // Funtion to Get Addresses List
    public function index()
    {

        $addresss_list = $this->addressService->getAddressesList();

        return ResponseHelper::success(AddressResource::collection($addresss_list), "Addresses Returned Successfully.", 200);

    }

    // Funtion to Get Address Details
    public function show(int $id)
    {

        $address_details =  $this->addressService->getAddressDetails($id);

        return ResponseHelper::success(new AddressResource($address_details), "Address #($id) Returned Successfully.", 200);

    }

    // Funtion To Add New Address
    public function store(Request $request)
    {

        try {

            $address_details = $this->addressService->addNewAddress($request->all());

            return ResponseHelper::success($address_details,"User Address Add Successfully.",201);
        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // Funtion To Update Address
    public function update(Request $request, int $id)
    {
        try {

            $address_details =  $this->addressService->updateAddress($request->all(), $id);

            return ResponseHelper::success($address_details,"User Address Updated Successfully.",201);

        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // Funtion To Delete Address
    public function destroy(Request $request, int $id)
    {
        try {

            $this->addressService->deleteAddress($request->all(), $id);

            return ResponseHelper::success(null, "User Address Deleted Successfully.", 201);
        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

}
