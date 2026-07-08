<?php

namespace App\Repositories\Dashboard;

use App\Models\SiteTheme;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class SiteThemeRepository
{

    // getSiteThemeList Funtion To Get Shipping Methods List
    public function getSiteThemeList()
    {
        return SiteTheme::all();
    }

    // getSiteThemeDetails Funtion To Get Shipping Method Details
    public function getSiteThemeDetails(int $id)
    {
        return  SiteTheme::findOrFail($id);
    }

    // addNewSiteTheme Funtion To Add new Shipping Method
    public function addNewSiteTheme(array $site_theme_request)
    {
        return SiteTheme::create($site_theme_request);
    }

    // updateSiteTheme Funtion To Update Shipping Method info
    public function updateSiteTheme(SiteTheme $siteTheme, array $site_theme_request)
    {
        $siteTheme->update($site_theme_request);
        return $siteTheme;
    }

    // deleteSiteTheme Funtion To Delete Shipping Method
    public function deleteSiteTheme(SiteTheme $siteTheme)
    {
        $siteTheme->delete();
        return $siteTheme;
    }
}

