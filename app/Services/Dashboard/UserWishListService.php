<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\UserWishListRepository;

class UserWishListService
{

    protected $userWishListRepository;

    public function __construct(UserWishListRepository $userWishListRepository)
    {
        $this->userWishListRepository = $userWishListRepository;
    }

    // Funtion To Get User Wish List List
    public function getUserWishListsList()
    {
        return  $this->userWishListRepository->getUserWishListsList();
    }

    // getUserWishListDetails Funtion To Get User Wish List Details
    public function getUserWishListDetails(int $id)
    {
        return $this->userWishListRepository->getUserWishListDetails($id);
    }

    // addNewUserWishList Funtion To Add new User Wish List
    public function addNewUserWishList(array $user_wish_list_request)
    {
        return $this->userWishListRepository->addNewUserWishList($user_wish_list_request);
    }

    // updateUserWishList Funtion To Update User Wish List info
    public function updateUserWishList(array $user_wish_list_request, int $id)
    {
        $user_wish_list_details = $this->userWishListRepository->getUserWishListDetails($id);
        return $this->userWishListRepository->updateUserWishList($user_wish_list_details, $user_wish_list_request);
    }

    // deleteUserWishList Funtion To Delete User Wish List
    public function deleteUserWishList($user_wish_list_request, int $id)
    {
        try {
            $user_wish_list_details = $this->userWishListRepository->getUserWishListDetails($id);
            return $this->userWishListRepository->deleteUserWishList($user_wish_list_details);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}

