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
        Route::GET('lookup-type/{lookupType}', [LookupTypeController::class, 'show']);
        Route::POST('lookup-type', [LookupTypeController::class, 'store']);
        Route::POST('lookup-type/{lookupType}', [LookupTypeController::class, 'update']);
        Route::DELETE('lookup-type/{lookupValue}', [LookupTypeController::class, 'destroy']);

    /***************************************** Lookup Values *******************************************/

        // Route::apiResource('lookup-value', LookupValueController::class);

        Route::GET('lookup-value', [LookupValueController::class, 'index']);
        Route::GET('lookup-value/{lookupValue}', [LookupValueController::class, 'show']);
        Route::POST('lookup-value', [LookupValueController::class, 'store']);
        Route::POST('lookup-value/{lookupValue}', [LookupValueController::class, 'update']);
        Route::DELETE('lookup-value/{lookupValue}', [LookupValueController::class, 'destroy']);

    /***************************************** Users *******************************************/

        // Route::apiResource('user', UserController::class);

        Route::GET('user', [UserController::class, 'index']);
        Route::GET('user/{user}', [UserController::class, 'show']);
        Route::POST('user', [UserController::class, 'store']);
        Route::POST('user/{user}', [UserController::class, 'update']);
        Route::DELETE('user/{user}', [UserController::class, 'destroy']);

    /***************************************** Addresses *******************************************/

        // Route::apiResource('address', AddressController::class);

        Route::GET('address', [AddressController::class, 'index']);
        Route::GET('address/{address}', [AddressController::class, 'show']);
        Route::POST('address', [AddressController::class, 'store']);
        Route::POST('address/{address}', [AddressController::class, 'update']);
        Route::DELETE('address/{address}', [AddressController::class, 'destroy']);

    /***************************************** Category *******************************************/

        // Route::apiResource('category', CategoryController::class);

        Route::GET('category', [CategoryController::class, 'index']);
        Route::GET('category/{category}', [CategoryController::class, 'show']);
        Route::POST('category', [CategoryController::class, 'store']);
        Route::POST('category/{category}', [CategoryController::class, 'update']);
        Route::DELETE('category/{category}', [CategoryController::class, 'destroy']);

    /***************************************** Products *******************************************/

        // Route::apiResource('product', ProductController::class);

        Route::GET('product', [ProductController::class, 'index']);
        Route::GET('product/{product}', [ProductController::class, 'show']);
        Route::POST('product', [ProductController::class, 'store']);
        Route::POST('product/{product}', [ProductController::class, 'update']);
        Route::DELETE('product/{product}', [ProductController::class, 'destroy']);

    /***************************************** Shipping Methods *******************************************/

        // Route::apiResource('shipping-method', ShippingMethodController::class);

        Route::GET('shipping-method', [ShippingMethodController::class, 'index']);
        Route::GET('shipping-method/{shippingMethod}', [ShippingMethodController::class, 'show']);
        Route::POST('shipping-method', [ShippingMethodController::class, 'store']);
        Route::POST('shipping-method/{shippingMethod}', [ShippingMethodController::class, 'update']);
        Route::DELETE('shipping-method/{shippingMethod}', [ShippingMethodController::class, 'destroy']);

    /***************************************** Coupons *******************************************/

        // Route::apiResource('coupon', CouponController::class);

        Route::GET('coupon', [CouponController::class, 'index']);
        Route::GET('coupon/{coupon}', [CouponController::class, 'show']);
        Route::POST('coupon', [CouponController::class, 'store']);
        Route::POST('coupon/{coupon}', [CouponController::class, 'update']);
        Route::DELETE('coupon/{coupon}', [CouponController::class, 'destroy']);

    /***************************************** Orders & Orders Details *******************************************/

        Route::GET('/get-orders-list', [OrderController::class, 'getOrdersList']);
        Route::GET('/get-order-details/{order}', [OrderController::class, 'getOrderDetails']);
        Route::POST('/confirm-order/{order}', [OrderController::class, 'confirmOrder']);

    /***************************************** Cart Product *******************************************/

        // Route::apiResource('cart-product', CartProductController::class);

        Route::GET('cart-product', [CartProductController::class, 'index']);
        Route::GET('cart-product/{cartProduct}', [CartProductController::class, 'show']);

    /***************************************** User Wish List *******************************************/

        // Route::apiResource('user-wish-list', UserWishListController::class);

        Route::GET('user-wish-list', [UserWishListController::class, 'index']);
        Route::GET('user-wish-list/{userWishlist}', [UserWishListController::class, 'show']);

    /***************************************** Product Review *******************************************/

        Route::GET('product-review', [ProductReviewController::class, 'index']);
        Route::GET('product-review/{productReview}', [ProductReviewController::class, 'show']);
        Route::DELETE('product-review/{productReview}', [ProductReviewController::class, 'destroy']);

    /***************************************** Site Theme *******************************************/

        // Route::apiResource('site-theme', SiteThemeController::class);

        Route::GET('site-theme', [SiteThemeController::class, 'index']);
        Route::GET('site-theme/{siteTheme}', [SiteThemeController::class, 'show']);
        Route::POST('site-theme', [SiteThemeController::class, 'store']);
        Route::POST('site-theme/{siteTheme}', [SiteThemeController::class, 'update']);
        Route::DELETE('site-theme/{siteTheme}', [SiteThemeController::class, 'destroy']);

    /***************************************** Site Images *******************************************/

        // Route::apiResource('site-image', SiteImageController::class);

        Route::GET('site-media', [SiteMediaController::class, 'index']);
        Route::GET('site-media/{siteMedia}', [SiteMediaController::class, 'show']);
        Route::POST('site-media', [SiteMediaController::class, 'store']);
        Route::POST('site-media/{siteMedia}', [SiteMediaController::class, 'update']);
        Route::DELETE('site-media/{siteMedia}', [SiteMediaController::class, 'destroy']);

});

Route::group(['prefix' => 'client'], function () {

    /***************************************** Index *******************************************/

        Route::GET('index', [MainController::class, 'index']);

        /***************************************** Site Theme *******************************************/

        Route::GET('get-site-theme', [MainController::class, 'getSiteTheme']);

    /***************************************** Site Images *******************************************/

        Route::get('/media/{siteMedia}/stream', [MainController::class, 'stream'])->name('media.stream');

        Route::GET('get-site-media', [MainController::class, 'getSiteMedia']);

    /***************************************** Product By Category *******************************************/

        Route::get('/reel/{product}/stream', [MainProductController::class, 'stream'])->name('reel.stream');

        // Define a route to Get Products List
        Route::GET('/get-products-list/{category}', [MainProductController::class, 'getProductsList']);

        // Define a route to Get Product Details
        Route::GET('/get-product-details/{product}', [MainProductController::class, 'getProductDetails']);

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
        Route::GET('/get-user-wish-list/{user}', [MainWishListController::class, 'getUserWishList']);

        // Define a route to Get Orders List Orders
        Route::POST('/add-to-wish-list', [MainWishListController::class, 'addToWishList']);

        // Define a route to Get Orders List Orders
        Route::POST('/delete-wish-list', [MainWishListController::class, 'deleteWishList']);

    /***************************************** User Cart *******************************************/

        // Define a route to Orders Details
        Route::GET('/get-user-cart-list/{user}', [MainCartController::class, 'getUserCartList']);

        // Define a route to Get Orders List Orders
        Route::POST('/add-to-cart', [MainCartController::class, 'addToCart']);

        // Define a route to Get Orders List Orders
        Route::POST('/update-cart-details/{cartProduct}', [MainCartController::class, 'updateCartDetails']);

        // Define a route to Orders Details
        Route::POST('/delete-cart-list', [MainCartController::class, 'deleteCartList']);

    /***************************************** Orders *******************************************/

        // Define a route to Confirm Order
        Route::POST('/place-new-order', [MainOrderController::class, 'addNewOrder']);

        // Define a route to Confirm Order
        Route::GET('/get-user-orders/{user}', [MainOrderController::class, 'getUserOrders']);

});



