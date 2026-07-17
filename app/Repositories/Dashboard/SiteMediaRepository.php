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

    // updateSiteMedia Funtion To Update Shipping Method info
    public function updateSiteMedia(object $siteMedia, array $site_media_request)
    {
        $siteMedia->update($site_media_request);
        return $siteMedia;
    }

    // deleteSiteMedia Funtion To Delete Shipping Method
    public function deleteSiteMedia(object $siteMedia)
    {
        $siteMedia->delete();
        return $siteMedia;
    }

    public function findById(int $media_id)
    {
        return SiteMedia::findOrFail($media_id);
    }

    public function deleteMedia(object $media)
    {
        return $media?->delete();
    }
}

