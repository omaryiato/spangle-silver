<?php

namespace App\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use App\Models\UserWishlist;


class UserWishListRepository
{

    // getUserWishlistsList Funtion To Get UserWishlists List
    public function getUserWishlistsList()
    {
        return UserWishlist::with(['user','product'])->get();
    }

    // getUserWishlistDetails Funtion To Get UserWishlist Details
    public function getUserWishlistDetails(int $id)
    {
        return UserWishlist::with(['user','product'])->findorfail($id);
    }

    // addNewUserWishlist Funtion To Add new UserWishlist
    public function addNewUserWishlist(array $user_wish_list_request)
    {
        return UserWishlist::create($user_wish_list_request);
    }

    // updateUserWishlist Funtion To Update UserWishlist info
    public function updateUserWishlist(UserWishlist $userWishList, array $user_wish_list_request)
    {
        $userWishList->update($user_wish_list_request);
        return $userWishList;
    }

    // deleteUserWishlist Funtion To Delete UserWishlist
    public function deleteUserWishlist(UserWishlist $userWishList)
    {
        $userWishList->delete();
        return $userWishList;
    }
}

