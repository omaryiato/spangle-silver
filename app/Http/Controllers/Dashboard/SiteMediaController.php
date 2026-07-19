<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SiteMedia\AddMedia;
use App\Http\Requests\Dashboard\SiteMedia\UpdateMedia;
use App\Http\Resources\SiteMediaResource;
use App\Models\SiteMedia;
use Illuminate\Http\Request;
use App\Services\Dashboard\SiteMediaService;

class SiteMediaController extends Controller
{
    protected $siteMediaService;

    public function __construct(SiteMediaService $siteMediaService)
    {
        $this->siteMediaService = $siteMediaService;
    }

    public function index(){
        $site_media_list = $this->siteMediaService->getSiteMediaList();
        return ResponseHelper::success(
                SiteMediaResource::collection($site_media_list),
                [
                    'en' => trans('validation.data_retrieved'),
                    'ar' => trans('validation.data_retrieved'),
                ],
                200);
    }

    //  Funtion to Get User Details
    public function show(SiteMedia $siteMedia)
    {

        $site_media_details =  $this->siteMediaService->getSiteMediaDetails($siteMedia);

        return ResponseHelper::success(
                new SiteMediaResource($site_media_details),
                [
                    'en' => trans('validation.data_retrieved'),
                    'ar' => trans('validation.data_retrieved'),
                ],
                200);

    }

    //  Funtion To Add New User
    public function store(AddMedia $request)
    {
        try{

            $site_media_details = $this->siteMediaService->addNewSiteMedia($request);
            return ResponseHelper::success(
                new SiteMediaResource($site_media_details),
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
    public function update(UpdateMedia $request, SiteMedia $siteMedia)
    {
        try {

            $site_media_details =  $this->siteMediaService->updateSiteMedia($request, $siteMedia);

            if (!$site_media_details) {
                return ResponseHelper::error(
                    new SiteMediaResource($site_media_details),
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                $site_media_details,
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
    public function destroy(Request $request, SiteMedia $siteMedia)
    {
        try {

            $site_media_details = $this->siteMediaService->deleteSiteMedia($siteMedia);

            if (!$site_media_details) {
                return ResponseHelper::error(
                    $site_media_details,
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

    public function stream(int $id)
    {
        $media_details = $this->siteMediaService->stream($id);

        if(!$media_details){
            return ResponseHelper::error(
                    $media_details,
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
        }

        return $media_details;
    }
}
