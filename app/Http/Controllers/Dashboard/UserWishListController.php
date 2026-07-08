<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\UserWishListResource;
use Illuminate\Http\Request;
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

        return ResponseHelper::success(UserWishListResource::collection($cart_products_list), "UserWishLists Returned Successfully.", 200);

    }

    // Funtion to Get User Wish List Details
    public function show(int $id)
    {

        $user_wish_list_details =  $this->userWishListService->getUserWishListDetails($id);

        return ResponseHelper::success(new UserWishListResource($user_wish_list_details), "Address #($id) Returned Successfully.", 200);

    }

    // Funtion To Add New User Wish List
    public function store(Request $request)
    {

        try {

            $user_wish_list_details = $this->userWishListService->addNewUserWishList($request->all());

            return ResponseHelper::success($user_wish_list_details,"User Address Add Successfully.",201);
        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // Funtion To Update User Wish List
    public function update(Request $request, int $id)
    {
        try {

            $user_wish_list_details =  $this->userWishListService->updateUserWishList($request->all(), $id);

            return ResponseHelper::success($user_wish_list_details,"User Address Updated Successfully.",201);

        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }

    // Funtion To Delete User Wish List
    public function destroy(Request $request, int $id)
    {
        try {

            $this->userWishListService->deleteUserWishList($request->all(), $id);

            return ResponseHelper::success(null, "User Address Deleted Successfully.", 201);
        } catch (\Exception $exception) {
            return ResponseHelper::error($request->all(),'Error -> ' . $exception->getMessage(),400);
        }
    }
}
