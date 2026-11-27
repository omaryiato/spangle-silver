<?php

namespace App\Services\Client;

use App\Helpers\ContactMessageHelper;
use App\Repositories\Client\MainWishListRepository;
use App\Services\ActingUserService;

class MainWishListService
{
    public function __construct(
        protected MainWishListRepository $mainWishListRepository,
        protected ContactMessageHelper $contactMessageHelper,
        protected ActingUserService $actingUserService,
    ) {}

    public function getUserWishList()
    {
        return $this->mainWishListRepository->getUserWishList($this->actingUserService->id());
    }

    public function addToWishList($wishlist_request)
    {
        return $this->mainWishListRepository->addToWishList($this->prepareWishListRequest($wishlist_request));
    }

    public function deleteWishList($wishlist_request)
    {
        if (! isset($wishlist_request['wishlist_id'])) {
            return $this->mainWishListRepository->deleteUserWishList($this->actingUserService->id());
        }
        $wishlist_details = $this->mainWishListRepository->getWishListDetails($wishlist_request['wishlist_id']);

        return $this->mainWishListRepository->deleteWishList($wishlist_details);

    }

    public function prepareWishListRequest(array $wishlist_request)
    {
        $request_data = [
            'user_id' => $this->actingUserService->id(),
            'product_id' => $wishlist_request['product_id'] ?? null,
        ];

        return $request_data;
    }
}
