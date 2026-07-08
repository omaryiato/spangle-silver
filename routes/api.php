<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\SiteImageController;
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

        Route::apiResource('lookup-type', LookupTypeController::class);

    /***************************************** Lookup Values *******************************************/

        Route::apiResource('lookup-value', LookupValueController::class);

    /***************************************** Users *******************************************/

        Route::apiResource('user', UserController::class);

    /***************************************** Addresses *******************************************/

        Route::apiResource('address', AddressController::class);

    /***************************************** Category *******************************************/

        Route::apiResource('category', CategoryController::class);

    /***************************************** Products *******************************************/

        // Define a route to Get Products List
        Route::GET('/get-products-list', [ProductController::class, 'getProductsList']);

        Route::apiResource('product', CategoryController::class);

    /***************************************** Shipping Methods *******************************************/

        // Define a route to Get Shipping Methods List
        Route::GET('/get-shipping-methods-list', [ShippingMethodController::class, 'getShippingMethodsList']);

        Route::apiResource('shipping-method', ShippingMethodController::class);

    /***************************************** Coupons *******************************************/

        Route::apiResource('coupon', CouponController::class);

    /***************************************** Orders & Orders Details *******************************************/

        // Define a route to Get Orders List Orders
        Route::GET('/get-orders-list', [OrderController::class, 'getOrdersList']);

        // Define a route to Orders Details
        Route::GET('/get-order-details/{id}', [OrderController::class, 'getOrderDetails']);

        // Define a route to Confirm Order
        Route::POST('/confirm-order/{id}', [OrderController::class, 'confirmOrder']);

    /***************************************** Cart Product *******************************************/

        Route::apiResource('cart-product', CartProductController::class);

    /***************************************** User Wish List *******************************************/

        Route::apiResource('user-wish-list', UserWishListController::class);

    /***************************************** Product Review *******************************************/

        Route::apiResource('product-review', ProductReviewController::class);

    /***************************************** Site Theme *******************************************/

        Route::apiResource('site-theme', SiteThemeController::class);

    /***************************************** Site Images *******************************************/

        Route::apiResource('site-image', SiteImageController::class);

});

Route::group(['prefix' => 'client'], function () {

    /***************************************** Index *******************************************/

        Route::GET('index', [MainController::class, 'index']);

        /***************************************** Site Theme *******************************************/

        Route::GET('get-site-theme', [MainController::class, 'getSiteTheme']);

    /***************************************** Site Images *******************************************/

        Route::GET('get-site-image', [MainController::class, 'getSiteMedia']);

    /***************************************** Product By Category *******************************************/

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



