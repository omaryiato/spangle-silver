<?php

namespace App\Services\Client;

use App\Helpers\ContactMessageHelper;
use App\Repositories\Client\MainWishListRepository;

class MainWishListService
{
    public function __construct(
        protected MainWishListRepository $mainWishListRepository,
        protected ContactMessageHelper $contactMessageHelper
    ) {}


    public function getUserWishList(int $user_id)
    {
        return $this->mainWishListRepository->getUserWishList($user_id);
    }

    public function addToWishList($wishlist_request)
    {
        return $this->mainWishListRepository->addToWishList($wishlist_request);
    }

    public function deleteWishList($wishlist_request)
    {
        if(isset($wishlist_request['user_id'])){
            return $this->mainWishListRepository->deleteUserWishList($wishlist_request['user_id']);
        }
        $wishlist_details = $this->mainWishListRepository->getWishListDetails($wishlist_request['wishlist_id']);
        return $this->mainWishListRepository->deleteWishList($wishlist_details);

    }

}
