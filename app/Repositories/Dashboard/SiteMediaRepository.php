<?php

namespace App\Repositories\Dashboard;

use App\Models\SiteMedia;



class SiteMediaRepository
{

    // getSiteMediaList Funtion To Get Shipping Methods List
    public function getSiteMediaList()
    {
        return SiteMedia::all();
    }

    // getSiteMediaDetails Funtion To Get Shipping Method Details
    public function getSiteMediaDetails(object $siteMedia)
    {
        return  $siteMedia;
    }

    // addNewSiteMedia Funtion To Add new Shipping Method
    public function addNewSiteMedia(array $site_media_request)
    {
        return SiteMedia::create($site_media_request);
    }

    public function findById(int $media_id)
    {
        return SiteMedia::findOrFail($media_id);
    }

    public function deleteSiteMedia(object $siteMedia)
    {
        return $siteMedia?->delete();
    }
}

