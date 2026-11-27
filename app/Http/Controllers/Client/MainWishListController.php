<?php

namespace App\Http\Controllers\Client;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\AddToWishList;
use App\Http\Requests\Client\DeleteWishList;
use App\Http\Resources\UserWishListResource;
use App\Services\Client\MainWishListService;

class MainWishListController extends Controller
{
    public function __construct(
        protected MainWishListService $mainWishListService
    ) {}

    public function getUserWishList()
    {
        $user_wishlist = $this->mainWishListService->getUserWishList();

        return ResponseHelper::success(
            UserWishListResource::collection($user_wishlist),
            [
                'en' => trans('validation.get_user_wishlist', [], 'en'),
                'ar' => trans('validation.get_user_wishlist', [], 'ar'),
            ],
            200
        );
    }

    public function addToWishList(AddToWishList $request)
    {

        $wishlist_details = $this->mainWishListService->addToWishList($request->validated());

        return ResponseHelper::success(
            new UserWishListResource($wishlist_details),
            [
                'en' => trans('validation.add_to_wishlist', [], 'en'),
                'ar' => trans('validation.add_to_wishlist', [], 'ar'),
            ],
            200
        );

    }

    public function deleteWishList(DeleteWishList $request)
    {

        $this->mainWishListService->deleteWishList($request->validated());

        return ResponseHelper::success(
            null,
            [
                'en' => trans('validation.delete_wishlist', [], 'en'),
                'ar' => trans('validation.delete_wishlist', [], 'ar'),
            ],
            200
        );

    }
}
