<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteImageResource;
use App\Models\SiteImage;
use Illuminate\Http\Request;
use App\Services\Dashboard\SiteImageService;

class SiteImageController extends Controller
{
    protected $siteImageService;

    public function __construct(SiteImageService $siteImageService)
    {
        $this->siteImageService = $siteImageService;
    }

    public function index(){
        $site_image_list = $this->siteImageService->getSiteImageList();
        return ResponseHelper::success(
                SiteImageResource::collection($site_image_list),
                [
                    'en' => trans('validation.data_retrieved'),
                    'ar' => trans('validation.data_retrieved'),
                ],
                200);
    }

    //  Funtion to Get User Details
    public function show(SiteImage $siteImage)
    {

        $site_image_details =  $this->siteImageService->getSiteImageDetails($siteImage->id);

        return ResponseHelper::success(
                new SiteImageResource($site_image_details),
                [
                    'en' => trans('validation.data_retrieved'),
                    'ar' => trans('validation.data_retrieved'),
                ],
                200);

    }

    //  Funtion To Add New User
    public function store(Request $request)
    {
        try{

            $site_image_details = $this->siteImageService->addNewSiteImage($request);
            return ResponseHelper::success(
                new SiteImageResource($site_image_details),
                [
                    'en' => trans('validation.data_added'),
                    'ar' => trans('validation.data_added'),
                ],
                201);

        } catch(\Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error'),
                    'ar' => trans('validation.exception_error'),
                ],
                $exception->getMessage(),
                500);
        }

    }

    //  Funtion To Update User
    public function update(Request $request, SiteImage $siteImage)
    {
        try {

            $site_image_details =  $this->siteImageService->updateSiteImage($request, $siteImage->id);

            if (!$site_image_details) {
                return ResponseHelper::error(
                    new SiteImageResource($site_image_details),
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                $site_image_details,
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

    // deleteUser Funtion To Delete User
    public function destroy(Request $request, SiteImage $siteImage)
    {
        try {

            $site_image_details = $this->siteImageService->deleteSiteImage($siteImage->id);

            if (!$site_image_details) {
                return ResponseHelper::error(
                    $site_image_details,
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
