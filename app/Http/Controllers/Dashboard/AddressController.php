<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Address\AddAddress;
use App\Http\Requests\Dashboard\Address\UpdateAddress;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Services\Dashboard\AddressService;
use Illuminate\Http\Request;

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

        return ResponseHelper::success(
            AddressResource::collection($addresss_list),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);

    }

    // Funtion to Get Address Details
    public function show(Address $address)
    {

        $address_details = $this->addressService->getAddressDetails($address);

        return ResponseHelper::success(
            new AddressResource($address_details),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);

    }

    // Funtion To Add New Address
    public function store(AddAddress $request)
    {

        $address_details = $this->addressService->addNewAddress($request->validated());

        return ResponseHelper::success(
            new AddressResource($address_details),
            [
                'en' => trans('validation.data_added', [], 'en'),
                'ar' => trans('validation.data_added', [], 'ar'),
            ],
            201);

    }

    // Funtion To Update Address
    public function update(UpdateAddress $request, Address $address)
    {

        $address_details = $this->addressService->updateAddress($request->validated(), $address);

        if (! $address_details) {
            return ResponseHelper::error(
                new AddressResource($address_details),
                [
                    'en' => trans('validation.data_not_found', [], 'en'),
                    'ar' => trans('validation.data_not_found', [], 'ar'),
                ],
                404);
        }

        return ResponseHelper::success(
            new AddressResource($address_details),
            [
                'en' => trans('validation.data_updated', [], 'en'),
                'ar' => trans('validation.data_updated', [], 'ar'),
            ],
            200);

    }

    // Funtion To Delete Address
    public function destroy(Request $request, Address $address)
    {

        $address_details = $this->addressService->deleteAddress($address);

        if (! $address_details) {
            return ResponseHelper::error(
                $address_details,
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
