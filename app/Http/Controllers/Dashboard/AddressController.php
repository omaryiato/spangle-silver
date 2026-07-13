<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\AddressService;
use App\Http\Resources\AddressResource;
use App\Helpers\ResponseHelper;
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
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);

    }

    // Funtion to Get Address Details
    public function show(Address $address)
    {

        $address_details =  $this->addressService->getAddressDetails($address->id);

        return ResponseHelper::success(
            new AddressResource($address_details),
            [
                'en' => trans('validation.data_retrieved'),
                'ar' => trans('validation.data_retrieved'),
            ],
            200);

    }

    // Funtion To Add New Address
    public function store(Request $request)
    {

        try {

            $address_details = $this->addressService->addNewAddress($request->all());

            return ResponseHelper::success(
                new AddressResource($address_details),
                [
                    'en' => trans('validation.data_added'),
                    'ar' => trans('validation.data_added'),
                ],
                201);
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

    // Funtion To Update Address
    public function update(Request $request, Address $address)
    {
        try {

            $address_details =  $this->addressService->updateAddress($request->all(), $address->id);

            if (!$address_details) {
                return ResponseHelper::error(
                    $address_details,
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                new AddressResource($address_details),
                [
                    'en' => trans('validation.data_updated'),
                    'ar' => trans('validation.data_updated'),
                ],
                201);

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

    // Funtion To Delete Address
    public function destroy(Request $request, Address $address)
    {
        try {

            $address_details = $this->addressService->deleteAddress($request->all(), $address->id);

            if (!$address_details) {
                return ResponseHelper::error(
                    $address_details,
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
