<?php

namespace App\Repositories\Dashboard;

use App\Models\SiteImage;



class SiteImageRepository
{

    // getSiteImageList Funtion To Get Shipping Methods List
    public function getSiteImageList()
    {
        return SiteImage::all();
    }

    // getSiteImageDetails Funtion To Get Shipping Method Details
    public function getSiteImageDetails(int $id)
    {
        return  SiteImage::findOrFail($id);
    }

    // addNewSiteImage Funtion To Add new Shipping Method
    public function addNewSiteImage(array $site_image_request)
    {
        return SiteImage::create($site_image_request);
    }

    // updateSiteImage Funtion To Update Shipping Method info
    public function updateSiteImage(SiteImage $siteImage, array $site_image_request)
    {
        $siteImage->update($site_image_request);
        return $siteImage;
    }

    // deleteSiteImage Funtion To Delete Shipping Method
    public function deleteSiteImage(SiteImage $siteImage)
    {
        $siteImage->delete();
        return $siteImage;
    }
}

