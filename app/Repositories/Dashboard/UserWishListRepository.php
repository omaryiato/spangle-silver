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
}

