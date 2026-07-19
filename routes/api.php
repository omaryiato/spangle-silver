<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\SiteMediaController;
use App\Http\Controllers\Dashboard\LookupTypeController;
use App\Http\Controllers\Dashboard\LookupValueController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\AddressController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ShippingMethodController;
use App\Http\Controllers\Dashboard\CouponController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\CartProductController;
use App\Http\Controllers\Dashboard\ProductReviewController;
use App\Http\Controllers\Dashboard\SiteThemeController;
use App\Http\Controllers\Dashboard\UserWishListController;
use App\Http\Controllers\Client\MainController;
use App\Http\Controllers\Client\MainProductController;
use App\Http\Controllers\Client\MainWishListController;
use App\Http\Controllers\Client\MainCartController;
use App\Http\Controllers\Client\MainOrderController;


Route::group(['prefix' => 'dashboard'], function () {

    /***************************************** Lookup Types *******************************************/

        // Route::apiResource('lookup-type', LookupTypeController::class);

        Route::GET('lookup-type', [LookupTypeController::class, 'index']);
        Route::GET('lookup-type/{type_id}', [LookupTypeController::class, 'show']);
        Route::POST('lookup-type', [LookupTypeController::class, 'store']);
        Route::POST('lookup-type/{type_id}', [LookupTypeController::class, 'update']);
        Route::POST('lookup-type/{type_id}', [LookupTypeController::class, 'destroy']);

    /***************************************** Lookup Values *******************************************/

        // Route::apiResource('lookup-value', LookupValueController::class);

        Route::GET('lookup-value', [LookupValueController::class, 'index']);
        Route::GET('lookup-value/{value_id}', [LookupValueController::class, 'show']);
        Route::POST('lookup-value', [LookupValueController::class, 'store']);
        Route::POST('lookup-value/{value_id}', [LookupValueController::class, 'update']);
        Route::POST('lookup-value/{value_id}', [LookupValueController::class, 'destroy']);

    /***************************************** Users *******************************************/

        // Route::apiResource('user', UserController::class);

        Route::GET('user', [UserController::class, 'index']);
        Route::GET('user/{user_id}', [UserController::class, 'show']);
        Route::POST('user', [UserController::class, 'store']);
        Route::POST('user/{user_id}', [UserController::class, 'update']);
        Route::POST('user/{user_id}', [UserController::class, 'destroy']);

    /***************************************** Addresses *******************************************/

        // Route::apiResource('address', AddressController::class);

        Route::GET('address', [AddressController::class, 'index']);
        Route::GET('address/{address_id}', [AddressController::class, 'show']);
        Route::POST('address', [AddressController::class, 'store']);
        Route::POST('address/{address_id}', [AddressController::class, 'update']);
        Route::POST('address/{address_id}', [AddressController::class, 'destroy']);

    /***************************************** Category *******************************************/

        // Route::apiResource('category', CategoryController::class);

        Route::GET('category', [CategoryController::class, 'index']);
        Route::GET('category/{category_id}', [CategoryController::class, 'show']);
        Route::POST('category', [CategoryController::class, 'store']);
        Route::POST('category/{category_id}', [CategoryController::class, 'update']);
        Route::POST('category/{category_id}', [CategoryController::class, 'destroy']);

    /***************************************** Products *******************************************/

        // Route::apiResource('product', ProductController::class);

        Route::GET('product', [ProductController::class, 'index']);
        Route::GET('product/{product_id}', [ProductController::class, 'show']);
        Route::POST('product', [ProductController::class, 'store']);
        Route::POST('product/{product_id}', [ProductController::class, 'update']);
        Route::POST('product/{product_id}', [ProductController::class, 'destroy']);

    /***************************************** Shipping Methods *******************************************/

        // Route::apiResource('shipping-method', ShippingMethodController::class);

        Route::GET('shipping-method', [ShippingMethodController::class, 'index']);
        Route::GET('shipping-method/{method_id}', [ShippingMethodController::class, 'show']);
        Route::POST('shipping-method', [ShippingMethodController::class, 'store']);
        Route::POST('shipping-method/{method_id}', [ShippingMethodController::class, 'update']);
        Route::POST('shipping-method/{method_id}', [ShippingMethodController::class, 'destroy']);

    /***************************************** Coupons *******************************************/

        // Route::apiResource('coupon', CouponController::class);

        Route::GET('coupon', [CouponController::class, 'index']);
        Route::GET('coupon/{coupon_id}', [CouponController::class, 'show']);
        Route::POST('coupon', [CouponController::class, 'store']);
        Route::POST('coupon/{coupon_id}', [CouponController::class, 'update']);
        Route::POST('coupon/{coupon_id}', [CouponController::class, 'destroy']);

    /***************************************** Orders & Orders Details *******************************************/

        Route::GET('/get-orders-list', [OrderController::class, 'getOrdersList']);
        Route::GET('/get-order-details/{id}', [OrderController::class, 'getOrderDetails']);
        Route::POST('/confirm-order/{id}', [OrderController::class, 'confirmOrder']);

    /***************************************** Cart Product *******************************************/

        // Route::apiResource('cart-product', CartProductController::class);

        Route::GET('cart-product', [CartProductController::class, 'index']);
        Route::GET('cart-product/{cart_id}', [CartProductController::class, 'show']);

    /***************************************** User Wish List *******************************************/

        // Route::apiResource('user-wish-list', UserWishListController::class);

        Route::GET('user-wish-list', [UserWishListController::class, 'index']);
        Route::GET('user-wish-list/{wish_id}', [UserWishListController::class, 'show']);

    /***************************************** Product Review *******************************************/

        Route::GET('product-review', [ProductReviewController::class, 'index']);
        Route::GET('product-review/{review_id}', [ProductReviewController::class, 'show']);
        Route::POST('product-review/{review_id}', [ProductReviewController::class, 'destroy']);

    /***************************************** Site Theme *******************************************/

        // Route::apiResource('site-theme', SiteThemeController::class);

        Route::GET('site-theme', [SiteThemeController::class, 'index']);
        Route::GET('site-theme/{theme_id}', [SiteThemeController::class, 'show']);
        Route::POST('site-theme', [SiteThemeController::class, 'store']);
        Route::POST('site-theme/{theme_id}', [SiteThemeController::class, 'update']);
        Route::POST('site-theme/{theme_id}', [SiteThemeController::class, 'destroy']);

    /***************************************** Site Images *******************************************/

        // Route::apiResource('site-image', SiteImageController::class);

        Route::GET('site-media', [SiteMediaController::class, 'index']);
        Route::GET('site-media/{media_id}', [SiteMediaController::class, 'show']);
        Route::POST('site-media', [SiteMediaController::class, 'store']);
        Route::POST('site-media/{media_id}', [SiteMediaController::class, 'update']);
        Route::POST('site-media/{media_id}', [SiteMediaController::class, 'destroy']);

});

Route::group(['prefix' => 'client'], function () {

    /***************************************** Index *******************************************/

        Route::GET('index', [MainController::class, 'index']);

        /***************************************** Site Theme *******************************************/

        Route::GET('get-site-theme', [MainController::class, 'getSiteTheme']);

    /***************************************** Site Images *******************************************/

        Route::get('/media/{media_id}/stream', [MainController::class, 'stream'])->name('media.stream');

        Route::GET('get-site-media', [MainController::class, 'getSiteMedia']);

    /***************************************** Product By Category *******************************************/

        Route::get('/reel/{product_id}/stream', [MainProductController::class, 'stream'])->name('reel.stream');

        // Define a route to Get Products List
        Route::GET('/get-products-list/{category_id}', [MainProductController::class, 'getProductsList']);

        // Define a route to Get Product Details
        Route::GET('/get-product-details/{product_id}', [MainProductController::class, 'getProductDetails']);

        // Define a route to Get Orders List Orders
        Route::POST('/review-product', [MainProductController::class, 'reviewProduct']);

    /***************************************** Shipping Methods *******************************************/

        // Define a route to Get Shipping Methods List
        Route::GET('/get-shipping-methods-list', [MainProductController::class, 'getShippingMethodsList']);

    /***************************************** Coupons *******************************************/

        // Define a route to Get Shipping Methods List
        Route::GET('/get-coupons-list', [MainProductController::class, 'getCouponsList']);

    /***************************************** User Wishlist *******************************************/

        // Define a route to Get Orders List Orders
        Route::GET('/get-user-wish-list/{user_id}', [MainWishListController::class, 'getUserWishList']);

        // Define a route to Get Orders List Orders
        Route::POST('/add-to-wish-list', [MainWishListController::class, 'addToWishList']);

        // Define a route to Get Orders List Orders
        Route::POST('/delete-wish-list', [MainWishListController::class, 'deleteWishList']);

    /***************************************** User Cart *******************************************/

        // Define a route to Orders Details
        Route::GET('/get-user-cart-list/{user_id}', [MainCartController::class, 'getUserCartList']);

        // Define a route to Get Orders List Orders
        Route::POST('/add-to-cart', [MainCartController::class, 'addToCart']);

        // Define a route to Get Orders List Orders
        Route::POST('/update-cart-details/{cart_id}', [MainCartController::class, 'updateCartDetails']);

        // Define a route to Orders Details
        Route::POST('/delete-cart-list', [MainCartController::class, 'deleteCartList']);

    /***************************************** Orders *******************************************/

        // Define a route to Confirm Order
        Route::POST('/place-new-order', [MainOrderController::class, 'addNewOrder']);

        // Define a route to Confirm Order
        Route::GET('/get-user-orders/{user_id}', [MainOrderController::class, 'getUserOrders']);

});



