<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteThemeResource;
use App\Models\SiteTheme;
use Illuminate\Http\Request;
use App\Services\Dashboard\SiteThemeService;

class SiteThemeController extends Controller
{
    protected $siteThemeService;

    public function __construct(SiteThemeService $siteThemeService)
    {
        $this->siteThemeService = $siteThemeService;
    }

    public function index(){
        $site_theme_list = $this->siteThemeService->getSiteThemeList();
        return ResponseHelper::success(
                SiteThemeResource::collection($site_theme_list),
                [
                    'en' => trans('validation.data_retrieved'),
                    'ar' => trans('validation.data_retrieved'),
                ],
                200);
    }

    //  Funtion to Get User Details
    public function show(SiteTheme $siteTheme)
    {

        $site_theme_details =  $this->siteThemeService->getSiteThemeDetails($siteTheme->id);

        return ResponseHelper::success(
                new SiteThemeResource($site_theme_details),
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

            $site_theme_details = $this->siteThemeService->addNewSiteTheme($request->all());
            return ResponseHelper::success(
                new SiteThemeResource($site_theme_details),
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
    public function update(Request $request, SiteTheme $siteTheme)
    {
        try {

            $site_theme_details =  $this->siteThemeService->updateSiteTheme($request->all(), $siteTheme->id);

            if (!$site_theme_details) {
                return ResponseHelper::error(
                    new SiteThemeResource($site_theme_details),
                    [
                        'en' => trans('validation.data_not_found'),
                        'ar' => trans('validation.data_not_found'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                $site_theme_details,
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
    public function destroy(Request $request, SiteTheme $siteTheme)
    {
        try {

            $site_theme_details = $this->siteThemeService->deleteSiteTheme($siteTheme->id);

            if (!$site_theme_details) {
                return ResponseHelper::error(
                    $site_theme_details,
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
