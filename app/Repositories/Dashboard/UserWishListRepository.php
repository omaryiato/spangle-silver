<?php

namespace App\Repositories\Dashboard;

use App\Models\UserWishlist;


class UserWishListRepository
{

    // getUserWishlistsList Funtion To Get UserWishlists List
    public function getUserWishlistsList()
    {
        return UserWishlist::with(['user','product'])->get();
    }

    // getUserWishlistDetails Funtion To Get UserWishlist Details
    public function getUserWishlistDetails(object $userWishlist)
    {
        return $userWishlist->load(['user','product']);
    }
}

