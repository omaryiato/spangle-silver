<?php

namespace App\Http\Controllers\Client;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserWishListResource;
use App\Services\Client\MainWishListService;
use Exception;
use Illuminate\Http\Request;

class MainWishListController extends Controller
{
    public function __construct(
        protected MainWishListService $mainWishListService
    ) {}

    public function getUserWishList(int $user_id)
    {
        $user_wishlist = $this->mainWishListService->getUserWishList($user_id);

        return ResponseHelper::success(
            UserWishListResource::collection($user_wishlist),
            [
                'en' => trans('validation.home_page'),
                'ar' => trans('validation.home_page'),
            ],
            200
        );
    }

    public function addToWishList(Request $request)
    {
        try{

            $wishlist_details = $this->mainWishListService->addToWishList($request->all());

            return ResponseHelper::success(
                new UserWishListResource($wishlist_details),
                [
                    'en' => trans('validation.home_page'),
                    'ar' => trans('validation.home_page'),
                ],
                200
            );
        } catch(Exception $exception){

        }
    }

    public function deleteWishList(Request $request)
    {
        try{

            $this->mainWishListService->deleteWishList($request);

            return ResponseHelper::success(
                null,
                [
                    'en' => trans('validation.home_page'),
                    'ar' => trans('validation.home_page'),
                ],
                200
            );
        } catch(Exception $exception){

        }
    }

}
