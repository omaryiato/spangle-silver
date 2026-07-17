<?php

namespace App\Repositories\Dashboard;

use App\Models\SiteTheme;



class SiteThemeRepository
{

    // getSiteThemeList Funtion To Get Shipping Methods List
    public function getSiteThemeList()
    {
        return SiteTheme::all();
    }

    // getSiteThemeDetails Funtion To Get Shipping Method Details
    public function getSiteThemeDetails(object $siteTheme)
    {
        return  $siteTheme;
    }

    // addNewSiteTheme Funtion To Add new Shipping Method
    public function addNewSiteTheme(array $site_theme_request)
    {
        return SiteTheme::create($site_theme_request);
    }

    // updateSiteTheme Funtion To Update Shipping Method info
    public function updateSiteTheme(object $siteTheme, array $site_theme_request)
    {
        $siteTheme->update($site_theme_request);
        return $siteTheme;
    }

    // deleteSiteTheme Funtion To Delete Shipping Method
    public function deleteSiteTheme(object $siteTheme)
    {
        $siteTheme->delete();
        return $siteTheme;
    }
}

