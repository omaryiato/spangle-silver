<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\SiteThemeResource;
use Illuminate\Http\Request;
use App\Services\Dashboard\SiteThemeService;

class SiteThemeController extends Controller
{
    protected $siteThemeService;

    public function __construct(SiteThemeService $siteThemeService)
    {
        $this->siteThemeService = $siteThemeService;
    }

    public function index(){
        $site_theme_list = $this->siteThemeService->getSiteThemeList();
        return ResponseHelper::success($site_theme_list);
    }

    //  Funtion to Get User Details
    public function show(int $id)
    {

        $site_theme_details =  $this->siteThemeService->getSiteThemeDetails($id);

        return ResponseHelper::success(
                new SiteThemeResource($site_theme_details),
                "User #($id) Returned Successfully.",
                200);

    }

    //  Funtion To Add New User
    public function store(Request $request)
    {
        try{

            $site_theme_details = $this->siteThemeService->addNewSiteTheme($request->all());
            return ResponseHelper::success($site_theme_details,"SiteTheme Added Successfully.",201);

        } catch(\Exception $exception){
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }

    }

    //  Funtion To Update User
    public function update(Request $request, int $id)
    {
        try {

            $site_theme_details =  $this->siteThemeService->updateSiteTheme($request->all(), $id);

            return ResponseHelper::success($site_theme_details,"SiteTheme Updated Successfully.",201);

        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // deleteUser Funtion To Delete User
    public function destroy(Request $request, int $id)
    {
        try {

            $this->siteThemeService->deleteSiteTheme($id);

            return ResponseHelper::success(
                    null,
                    "User Deleted Successfully.",
                    200);
        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }
}
