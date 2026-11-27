<?php

namespace App\Services\Dashboard;

use App\Models\SiteTheme;
use App\Repositories\Dashboard\SiteThemeRepository;
use App\Services\ActingUserService;
use App\Services\MediaStorageService;
use Illuminate\Http\UploadedFile;

class SiteThemeService
{
    protected $siteThemeRepository;

    public function __construct(SiteThemeRepository $siteThemeRepository, protected ActingUserService $actingUserService, protected MediaStorageService $mediaStorage)
    {
        $this->siteThemeRepository = $siteThemeRepository;
    }

    // getSiteThemeList Funtion To Get Shipping Methods List
    public function getSiteThemeList()
    {
        return $this->siteThemeRepository->getSiteThemeList();
    }

    // getSiteThemeDetails Funtion To Get Shipping Method Details
    public function getSiteThemeDetails(SiteTheme $siteTheme)
    {
        return $this->siteThemeRepository->getSiteThemeDetails($siteTheme);
    }

    // addNewSiteTheme Funtion To Add new Shipping Method
    public function addNewSiteTheme(object $request)
    {
        $site_theme_request = $request->validated();

        if ($request->hasFile('background_image')) {
            $background_image = $this->uploadThemeImage(
                $request->file('background_image'),
                $site_theme_request['theme_name']
            );

            $site_theme_request['background_image'] = $background_image;
        }

        try {
            return $this->siteThemeRepository->addNewSiteTheme($this->prepareRequestInfo($site_theme_request));
        } catch (\Throwable $exception) {
            $this->mediaStorage->delete($site_theme_request['background_image'] ?? null);
            throw $exception;
        }
    }

    // updateSiteTheme Funtion To Update Shipping Method info
    public function updateSiteTheme(object $request, SiteTheme $siteTheme)
    {
        $data = $request->validated();
        unset($data['background_image']);
        $oldPath = $siteTheme->background_image;
        $newPath = null;
        if ($request->hasFile('background_image')) {
            $newPath = $this->uploadThemeImage($request->file('background_image'), $siteTheme->theme_name);
            $data['background_image'] = $newPath;
        }
        try {
            $result = $this->siteThemeRepository->updateSiteTheme($siteTheme, $this->prepareRequestInfo($data, true));
        } catch (\Throwable $exception) {
            $this->mediaStorage->delete($newPath);
            throw $exception;
        }
        if ($newPath) {
            $this->mediaStorage->delete($oldPath);
        }

        return $result;
    }

    // deleteSiteTheme Funtion To Delete Shipping Method
    public function deleteSiteTheme(SiteTheme $siteTheme)
    {

        // $site_theme_details = $this->siteThemeRepository->getSiteThemeDetails($siteTheme);
        // if(!$site_theme_details){
        //     return null;
        // }
        return $this->siteThemeRepository->deleteSiteTheme($siteTheme);
    }

    public function prepareRequestInfo(array $request_info, bool $updating = false)
    {

        $request_data = [
            'theme_name' => $request_info['theme_name'] ?? null,
            'color_scheme' => $request_info['color_scheme'] ?? null,
            'font_style' => $request_info['font_style'] ?? null,
            'background_image' => $request_info['background_image'] ?? null,
            'borders' => $request_info['borders'] ?? null,
            'status' => $request_info['status'] ?? 1,
        ];

        if ($updating) {
            $request_data = array_intersect_key($request_data, $request_info);
        }

        return $this->actingUserService->attributes($request_data, $updating);
    }

    public function uploadThemeImage(UploadedFile $file, string $theme_name): string
    {
        return $this->mediaStorage->store($file, 'themes', true);
    }
}
