<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\SiteImageRepository;
use Illuminate\Support\Facades\File;


class SiteImageService
{

    protected $siteImageRepository;

    public function __construct(SiteImageRepository $siteImageRepository)
    {
        $this->siteImageRepository = $siteImageRepository;
    }

    // getSiteImageList Funtion To Get Shipping Methods List
    public function getSiteImageList()
    {
        return  $this->siteImageRepository->getSiteImageList();
    }

    // getSiteImageDetails Funtion To Get Shipping Method Details
    public function getSiteImageDetails(int $id)
    {
        return $this->siteImageRepository->getSiteImageDetails($id);
    }

    // addNewSiteImage Funtion To Add new Shipping Method
    public function addNewSiteImage($request)
    {
        $site_image_request = $request->all();

        if ($request->hasFile('image')) {
            $site_image = $this->uploadSiteImage(
                $request->file('image'),
                $site_image_request['type']
            );

            $site_image_request['image'] = $site_image;
        }
        return $this->siteImageRepository->addNewSiteImage($this->prepareRequestInfo($site_image_request));
    }

    // updateSiteImage Funtion To Update Shipping Method info
    public function updateSiteImage($request, int $id)
    {
        $site_image_request = $request->all();

        $site_image_details = $this->siteImageRepository->getSiteImageDetails($id);

        if(!$site_image_details){
            return null;
        }

        if ($request->hasFile('image')) {

            // Delete old image
            if ($site_image_details->image) {

                $old_path = public_path($site_image_details->image);

                if (File::exists($old_path)) {
                    File::delete($old_path);
                }
            }


            // Upload new image
            $site_image_request['image'] = $this->uploadSiteImage(
                $request->file('image'),
                $site_image_request['type']
            );

        } else {
            // keep old image
            $site_image_request['image'] = $site_image_details->image;
        }
        return $this->siteImageRepository->updateSiteImage($site_image_details, $this->prepareRequestInfo($site_image_request));
    }

    // deleteSiteImage Funtion To Delete Shipping Method
    public function deleteSiteImage(int $id)
    {

        $site_image_details = $this->siteImageRepository->getSiteImageDetails($id);
        if(!$site_image_details){
            return null;
        }
        return $this->siteImageRepository->deleteSiteImage($site_image_details);
    }

    public function uploadSiteImage($file, string $type)
    {
        $extension = $file->getClientOriginalExtension();

        $file_name = str_replace(' ', '_', $type) . '_' . date('dmYHis') . '.' . $extension;

        $folder_path = public_path("documents/site_images/{$type}/");


        if (!File::exists($folder_path)) {
            File::makeDirectory($folder_path, 0755, true);
        }


        $file->move($folder_path, $file_name);


        return "documents/site_images/{$type}/{$file_name}";
    }

    public function prepareRequestInfo(array $request_info)
    {
        $request_data = [
            'type' => $request_info['type'] ?? null,
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

