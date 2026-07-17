<?php

namespace App\Repositories\Dashboard;

use App\Models\User;



class UserRepository
{

    // getUsersList Funtion To Get Users List
    public function getUsersList()
    {
        return User::with([
            // User Addresses
            'addresses',

            // Orders
            'orders.shipping',
            'orders.payment',
            'orders.details.variant.product',
            'orders.details.variant.color',
            'orders.details.variant.size',
            'orders.details.variant.product.images',
            'orders.details.variant.product.material',
            'orders.details.variant.product.stone',

            // Cart
            'cart.variant.product',
            'cart.variant.color',
            'cart.variant.size',
            'cart.variant.product.images',
            'cart.variant.product.material',
            'cart.variant.product.stone',

            // Wishlist
            'wishlist.product',
            'wishlist.product.images',
            'wishlist.product.variants',
            'wishlist.product.material',
            'wishlist.product.stone',

        ])->get();
    }

    // getUserDetails Funtion To Get User Details
    public function getUserDetails(object $user)
    {
        return $user->load([
            // User Addresses
            'addresses',

            // Orders
            'orders.shipping',
            'orders.payment',
            'orders.details.variant.product',
            'orders.details.variant.color',
            'orders.details.variant.size',
            'orders.details.variant.product.images',
            'orders.details.variant.product.material',
            'orders.details.variant.product.stone',

            // Cart
            'cart.variant.product',
            'cart.variant.color',
            'cart.variant.size',
            'cart.variant.product.images',
            'cart.variant.product.material',
            'cart.variant.product.stone',

            // Wishlist
            'wishlist.product',
            'wishlist.product.images',
            'wishlist.product.variants',
            'wishlist.product.material',
            'wishlist.product.stone',

        ]);
    }

    // addNewUser Funtion To Add new User
    public function addNewUser(array $user_details)
    {
        return User::create($user_details);
    }

    // updateUser Funtion To Update User info
    public function updateUser(object $user, array $user_request)
    {
        $user->update($user_request);
        return $user;
    }

    // deleteUser Funtion To Delete User
    public function deleteUser(object $user)
    {
        $user->delete();
        return $user;
    }
}

