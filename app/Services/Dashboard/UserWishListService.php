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
    public function getUserWishListDetails(object $userWishlist)
    {
        return $this->userWishListRepository->getUserWishListDetails($userWishlist);
    }

}

