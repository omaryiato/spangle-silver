<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainControlPanelController;
use App\Http\Controllers\Dashboard\LookupTypeController;
use App\Http\Controllers\Dashboard\LookupValueController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\AddressController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ShippingMethodController;
use App\Http\Controllers\Dashboard\CouponController;
// use App\Http\Controllers\Dashboard\OrderController;



//     // Define a route to Check Hashkey
//     Route::GET('/control-panel-system', [MainControlPanelController::class, 'controlPanelSystem']);

// /*************************************** Dashboard APIs ******************************************/

//     // Define a route to Get All Active Employee List
//     Route::GET('/get-all-active-employee-list', [MainControlPanelController::class, 'getAllActiveEmployeeList'])->name('get_all_active_employee_list');

//     // Define a route to Get Employee Information
//     Route::GET('/get-employee-information', [MainControlPanelController::class, 'getEmployeeInformation'])->name('get_employee_information');

//     // Define a route to Get Employee Assigned Feature
//     Route::GET('/get-employee-assigned_feature', [MainControlPanelController::class, 'getEmployeeAssignedFeature']);

//     // Define a route to Delete all tables data
//     Route::POST('/truncate-all-tables', [MainControlPanelController::class, 'truncateAllTables'])->name('truncate_all_tables');

Route::group(['prefix' => 'dashboard'], function () {

    /***************************************** Lookup Types *******************************************/

        Route::apiResource('lookup-types', LookupTypeController::class);

    /***************************************** Lookup Values *******************************************/

        Route::apiResource('lookup-values', LookupValueController::class);

    /***************************************** Users *******************************************/

        Route::apiResource('users', UserController::class);

    /***************************************** Addresses *******************************************/

        Route::apiResource('addresses', AddressController::class);

    /***************************************** Category *******************************************/

        Route::apiResource('category', CategoryController::class);

    /***************************************** Products *******************************************/

        // Define a route to Get Products List
        Route::GET('/get-products-list', [ProductController::class, 'getProductsList']);

        // Define a route to Product Details
        Route::GET('/get-product-details', [ProductController::class, 'getProductDetails']);

        // Define a route to Add New Product
        Route::POST('/add-new-product', [ProductController::class, 'addNewProduct']);

        // Define a route to Update Product
        Route::POST('/update-product', [ProductController::class, 'updateProduct']);

        // Define a route to Delete Product
        Route::POST('/delete-product', [ProductController::class, 'deleteProduct']);

    /***************************************** Shipping Methods *******************************************/

        // Define a route to Get Shipping Methods List
        Route::GET('/get-shipping-methods-list', [ShippingMethodController::class, 'getShippingMethodsList']);

        // Define a route to Shipping Method Details
        Route::GET('/get-shipping-method-details', [ShippingMethodController::class, 'getShippingMethodDetails']);

        // Define a route to Add New Shipping Method
        Route::POST('/add-new-shipping-method', [ShippingMethodController::class, 'addNewShippingMethod']);

        // Define a route to Update Shipping Method
        Route::POST('/update-shipping-method', [ShippingMethodController::class, 'updateShippingMethod']);

        // Define a route to Delete Shipping Method
        Route::POST('/delete-shipping-method', [ShippingMethodController::class, 'deleteShippingMethod']);
    /***************************************** Coupons *******************************************/

        // Define a route to Get Coupons List
        Route::GET('/get-coupons-list', [CouponController::class, 'getCouponsList']);

        // Define a route to Coupons Details
        Route::GET('/get-coupon-details', [CouponController::class, 'getCouponDetails']);

        // Define a route to Add New Coupons
        Route::POST('/add-new-coupon', [CouponController::class, 'addNewCoupon']);

        // Define a route to Update Coupons
        Route::POST('/update-coupon', [CouponController::class, 'updateCoupon']);

        // Define a route to Delete Coupons
        Route::POST('/delete-coupon', [CouponController::class, 'deleteCoupon']);
    /***************************************** Orders & Orders Details *******************************************/

        // // Define a route to Get Orders List Orders
        // Route::GET('/get-orders-list', [OrderController::class, 'getOrdersList']);

        // // Define a route to Orders Details
        // Route::GET('/get-order-details', [OrderController::class, 'getOrderDetails']);

        // // Define a route to Confirm Order
        // Route::POST('/confirm-order', [OrderController::class, 'confirmOrder']);

});

/***************************************** Control Panel Chats *******************************************/

    // // Define a route to Get ControlPanel Request Messages List
    // Route::GET('/get-ControlPanel-request-messages-list', [ControlPanelRequestChatController::class, 'getControlPanelRequestMessagesList']);

    // // Define a route to Add new ControlPanel Request Message
    // Route::POST('/add-new-ControlPanel-request-message', [ControlPanelRequestChatController::class, 'addNewControlPanelRequestMessage']);

    // // Define a route to Update ControlPanel Request Message
    // Route::POST('/update-ControlPanel-request-message', [ControlPanelRequestChatController::class, 'updateControlPanelRequestMessage']);

    // // Define a route to Delete  ControlPanel Request Message
    // Route::POST('/delete-ControlPanel-request-message', [ControlPanelRequestChatController::class, 'deleteControlPanelRequestMessage']);

    // // Define a route to Read ControlPanel Request Message
    // Route::POST('/read-ControlPanel-request-message', [ControlPanelRequestChatController::class, 'readControlPanelRequestMessage']);


/****************************************************************************************************/
/***************************************** ControlPanel Setup *******************************************/
/****************************************************************************************************/



