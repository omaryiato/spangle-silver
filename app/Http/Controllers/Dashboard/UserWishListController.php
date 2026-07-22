<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserWishListResource;
use App\Models\UserWishlist;
use App\Services\Dashboard\UserWishListService;

class UserWishListController extends Controller
{
    protected $userWishListService;

    public function __construct(UserWishListService $userWishListService)
    {
        $this->userWishListService = $userWishListService;
    }

    // Funtion to Get User Wish List List
    public function index()
    {

        $cart_products_list = $this->userWishListService->getUserWishListsList();

        return ResponseHelper::success(
            UserWishListResource::collection($cart_products_list),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);

    }

    // Funtion to Get User Wish List Details
    public function show(UserWishlist $userWishlist)
    {

        $user_wish_list_details =  $this->userWishListService->getUserWishListDetails($userWishlist);

        return ResponseHelper::success(
            new UserWishListResource($user_wish_list_details),
            [
                'en' => trans('validation.data_retrieved', [], 'en'),
                'ar' => trans('validation.data_retrieved', [], 'ar'),
            ],
            200);

    }

}
