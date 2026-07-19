<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\SiteThemeRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Format;
use Intervention\Image\Laravel\Facades\Image;


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
    public function getSiteThemeDetails(object $siteTheme)
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
        return $this->siteThemeRepository->addNewSiteTheme($this->prepareRequestInfo($site_theme_request));
    }

    // updateSiteTheme Funtion To Update Shipping Method info
    public function updateSiteTheme(object $request, object $siteTheme)
    {
        // $site_theme_details = $this->siteThemeRepository->getSiteThemeDetails($siteTheme);
        // if(!$site_theme_details){
        //     return null;
        // }
        $site_theme_request = $request->validated();

        if ($request->hasFile('background_image')) {

            // Delete old image
            if ($siteTheme->background_image) {

                $old_path = public_path($siteTheme->background_image);

                if (File::exists($old_path)) {
                    File::delete($old_path);
                }
            }


            // Upload new image
            $site_theme_request['background_image'] = $this->uploadThemeImage(
                $request->file('background_image'),
                $site_theme_request['theme_name']
            );

        } else {
            // keep old image
            $site_theme_request['background_image'] = $siteTheme->background_image;
        }

        return $this->siteThemeRepository->updateSiteTheme($siteTheme,
        $this->prepareRequestInfo($site_theme_request));
    }

    // deleteSiteTheme Funtion To Delete Shipping Method
    public function deleteSiteTheme(object $siteTheme)
    {

        // $site_theme_details = $this->siteThemeRepository->getSiteThemeDetails($siteTheme);
        // if(!$site_theme_details){
        //     return null;
        // }
        return $this->siteThemeRepository->deleteSiteTheme($siteTheme);
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

    public function uploadThemeImage(UploadedFile $file, string $theme_name)
    {
        $extension = $file->getClientOriginalExtension();

        $file_name = str_replace(' ', '_', $theme_name) . '.' . $extension;

        $folder_path = public_path("documents/themes");


        if (!File::exists($folder_path)) {
            File::makeDirectory($folder_path, 0755, true);
        }

        $webp_name = pathinfo($file_name, PATHINFO_FILENAME) . '.webp';

        $image = Image::decode($file);

        // encode to webp
        $encoded = $image->encodeUsingFormat(
            Format::WEBP,
            quality: 85
        );

        // save encoded image
        $encoded->save("{$folder_path}/{$webp_name}");


        // $file->move($folder_path, $file_name);


        return "documents/themes/{$webp_name}";
    }

}

