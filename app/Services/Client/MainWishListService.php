<?php

namespace App\Services\Client;

use App\Helpers\ContactMessageHelper;
use App\Models\User;
use App\Models\UserWishlist;
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
        return $this->mainWishListRepository->addToWishList($this->prepareWishListRequest($wishlist_request));
    }

    public function deleteWishList($wishlist_request)
    {
        if(isset($wishlist_request['user_id'])){
            return $this->mainWishListRepository->deleteUserWishList($wishlist_request['user_id']);
        }
        $wishlist_details = $this->mainWishListRepository->getWishListDetails($wishlist_request['wishlist_id']);
        return $this->mainWishListRepository->deleteWishList($wishlist_details);

    }

    public function prepareWishListRequest(array $wishlist_request)
    {
        $request_data = [
            'user_id' => $wishlist_request['user_id'] ?? null,
            'product_id' => $wishlist_request['product_id'] ?? null,
        ];

        return $request_data;
    }

}
