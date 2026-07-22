<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\AddressService;
use App\Http\Resources\AddressResource;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Dashboard\Address\AddAddress;
use App\Http\Requests\Dashboard\Address\UpdateAddress;
use App\Models\Address;

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

        $address_details =  $this->addressService->getAddressDetails($address);

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

        try {

            $address_details = $this->addressService->addNewAddress($request->validated());

            return ResponseHelper::success(
                new AddressResource($address_details),
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

    // Funtion To Update Address
    public function update(UpdateAddress $request, Address $address)
    {
        try {

            $address_details =  $this->addressService->updateAddress($request->validated(), $address);

            if (!$address_details) {
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

    // Funtion To Delete Address
    public function destroy(Request $request, Address $address)
    {
        try {

            $address_details = $this->addressService->deleteAddress($request->all(), $address);

            if (!$address_details) {
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
