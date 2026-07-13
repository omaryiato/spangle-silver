<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\SiteThemeRepository;

class SiteThemeService
{

    protected $siteThemeRepository;

    public function __construct(SiteThemeRepository $siteThemeRepository)
    {
        $this->siteThemeRepository = $siteThemeRepository;
    }

    // getSiteThemeList Funtion To Get Shipping Methods List
    public function getSiteThemeList()
    {
        return  $this->siteThemeRepository->getSiteThemeList();
    }

    // getSiteThemeDetails Funtion To Get Shipping Method Details
    public function getSiteThemeDetails(int $id)
    {
        return $this->siteThemeRepository->getSiteThemeDetails($id);
    }

    // addNewSiteTheme Funtion To Add new Shipping Method
    public function addNewSiteTheme(array $site_theme_request)
    {
        return $this->siteThemeRepository->addNewSiteTheme($this->prepareRequestInfo($site_theme_request));
    }

    // updateSiteTheme Funtion To Update Shipping Method info
    public function updateSiteTheme(array $site_theme_request, int $id)
    {
        $site_theme_details = $this->siteThemeRepository->getSiteThemeDetails($id);
        if(!$site_theme_details){
            return null;
        }
        return $this->siteThemeRepository->updateSiteTheme($site_theme_details, $this->prepareRequestInfo($site_theme_request));
    }

    // deleteSiteTheme Funtion To Delete Shipping Method
    public function deleteSiteTheme(int $id)
    {

        $site_theme_details = $this->siteThemeRepository->getSiteThemeDetails($id);
        if(!$site_theme_details){
            return null;
        }
        return $this->siteThemeRepository->deleteSiteTheme($site_theme_details);
    }

    public function prepareRequestInfo(array $request_info)
    {

        $request_data = [
            'theme_name' => $request_info['theme_name'] ?? null,
            'color_scheme' => $request_info['color_scheme'] ?? null,
            'font_style' => $request_info['font_style'] ?? null,
            'background_image' => $request_info['background_image'] ?? null,
            'borders' => $request_info['borders'] ?? null,
            'status' => $request_info['status'] ?? null,
        ];


        if (isset($request_info['created_by'])) {
            $request_data['created_by'] = $request_info['created_by'];
        }


        if (isset($request_info['updated_by'])) {
            $request_data['updated_by'] = $request_info['updated_by'];
        }


        return $request_data;
    }

}

