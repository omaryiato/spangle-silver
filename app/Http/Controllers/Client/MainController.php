<?php

namespace App\Http\Controllers\Client;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SiteMediaResource;
use App\Http\Resources\SiteThemeResource;
use App\Models\SiteMedia;
use App\Services\Client\MainService;
use Exception;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function __construct(
        protected MainService $mainService
    ) {}

    public function index()
    {
        $all_data = $this->mainService->getAllActiveData();

        return ResponseHelper::success(
            CategoryResource::collection($all_data),
            [
                'en' => trans('validation.home_page', [], 'en'),
                'ar' => trans('validation.home_page', [], 'ar'),
            ],
            200
        );
    }

    public function getSiteTheme()
    {
        $site_theme = $this->mainService->getSiteTheme();

        return ResponseHelper::success(
            SiteThemeResource::collection($site_theme),
            [
                'en' => trans('validation.get_site_theme', [], 'en'),
                'ar' => trans('validation.get_site_theme', [], 'ar'),
            ],
            200
        );
    }
    public function getSiteMedia()
    {
        $site_media = $this->mainService->getSiteMedia();

        return ResponseHelper::success(
            SiteMediaResource::collection($site_media),
            [
                'en' => trans('validation.get_site_media', [], 'en'),
                'ar' => trans('validation.get_site_media', [], 'ar'),
            ],
            200
        );
    }

    public function stream(SiteMedia $siteMedia)
    {
        $media_details = $this->mainService->stream($siteMedia);

        if(!$media_details){
            return ResponseHelper::error(
                    $media_details,
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    404);
        }

        return $media_details;
    }

}
