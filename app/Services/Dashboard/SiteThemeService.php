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

        try {
            return $this->siteThemeRepository->addNewSiteTheme($site_theme_request);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // updateSiteTheme Funtion To Update Shipping Method info
    public function updateSiteTheme(array $site_theme_request, int $id)
    {

        try {
            $site_theme_details = $this->siteThemeRepository->getSiteThemeDetails($id);
            return $this->siteThemeRepository->updateSiteTheme($site_theme_details, $site_theme_request);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    // deleteSiteTheme Funtion To Delete Shipping Method
    public function deleteSiteTheme(int $id)
    {
        try {
            $site_theme_details = $this->siteThemeRepository->getSiteThemeDetails($id);
            return $this->siteThemeRepository->deleteSiteTheme($site_theme_details);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

