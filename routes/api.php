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
use App\Http\Controllers\ControlPanelPageController;
use App\Http\Controllers\ControlPanelFeatureController;
use App\Http\Controllers\ControlPanelRoleController;



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

        // Define a route to Get Lookup Type List
        Route::GET('/get-lookup-type-list', [LookupTypeController::class, 'getLookupTypeList']);

        // Define a route to Lookup Type Details
        Route::GET('/get-lookup-type-details', [LookupTypeController::class, 'getLookupTypeDetails']);

        // Define a route to Add New Lookup Type
        Route::POST('/add-new-lookup-type', [LookupTypeController::class, 'addNewLookupType']);

        // Define a route to Update Lookup Type
        Route::POST('/update-lookup-type', [LookupTypeController::class, 'updateLookupType']);

        // Define a route to Delete Lookup Type
        Route::POST('/delete-lookup-type', [LookupTypeController::class, 'deleteLookupType']);

    /***************************************** Lookup Values *******************************************/

        // Define a route to Get Lookup Values List
        Route::GET('/get-lookup-value-list', [LookupValueController::class, 'getLookupValueList']);

        // Define a route to Lookup Values Details
        Route::GET('/get-lookup-value-details', [LookupValueController::class, 'getLookupValueDetails']);

        // Define a route to Add New Lookup Values
        Route::POST('/add-new-lookup-value', [LookupValueController::class, 'addNewLookupValue']);

        // Define a route to Update Lookup Values
        Route::POST('/update-lookup-value', [LookupValueController::class, 'updateLookupValue']);

        // Define a route to Delete Lookup Values
        Route::POST('/delete-lookup-value', [LookupValueController::class, 'deleteLookupValue']);

    /***************************************** Users *******************************************/

        // Define a route to Get Users List
        Route::GET('/get-users-list', [UserController::class, 'getUsersList']);

        // Define a route to Users Details
        Route::GET('/get-user-details', [UserController::class, 'getUserDetails']);

        // Define a route to Add New Users
        Route::POST('/add-new-user', [UserController::class, 'addNewUser']);

        // Define a route to Update Users
        Route::POST('/update-user', [UserController::class, 'updateUser']);

        // Define a route to Delete Users
        Route::POST('/delete-user', [UserController::class, 'deleteUser']);

    /***************************************** Addresses *******************************************/

        // Define a route to Get Addresses List
        Route::GET('/get-addresses-list', [AddressController::class, 'getAddressesList']);

        // Define a route to Address Details
        Route::GET('/get-address-details', [AddressController::class, 'getAddressDetails']);

        // Define a route to Add New Address
        Route::POST('/add-new-address', [AddressController::class, 'addNewAddress']);

        // Define a route to Update Address
        Route::POST('/update-address', [AddressController::class, 'updateAddress']);

        // Define a route to Delete Address
        Route::POST('/delete-address', [AddressController::class, 'deleteAddress']);

    /***************************************** Category *******************************************/

        // Define a route to Get Category List
        Route::GET('/get-category-list', [CategoryController::class, 'getCategoryList']);

        // Define a route to Category Details
        Route::GET('/get-category-details', [CategoryController::class, 'getCategoryDetails']);

        // Define a route to Add New Category
        Route::POST('/add-new-category', [CategoryController::class, 'addNewCategory']);

        // Define a route to Update Category
        Route::POST('/update-category', [CategoryController::class, 'updateCategory']);

        // Define a route to Delete Category
        Route::POST('/delete-category', [CategoryController::class, 'deleteCategory']);

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



