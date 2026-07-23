<?php

namespace App\Repositories\Client;

use App\Models\User;
use App\Models\UserWishlist;

class MainWishListRepository
{

    public function getWishListDetails(int $id)
    {
        return UserWishlist::findOrFail($id);
    }
    public function getUserWishList(int $user_id)
    {
        return UserWishlist::with(['user','product'])->where('user_id', $user_id)->get();
    }

    public function addToWishList($wishlist_request)
    {
        return UserWishlist::create($wishlist_request);
    }

    public function deleteWishList(UserWishlist $wishlist_details)
    {
        $wishlist_details->delete();
        return $wishlist_details;
    }

    public function deleteUserWishList(int $user_id)
    {
        return UserWishlist::where('user_id', $user_id)->delete();
    }
}
