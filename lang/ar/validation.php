<?php
return [


    "exception_error" => "حدث خطا ما .",

    'login_user_required' => 'رقم ملف الموظف مطلوب.',
    'login_user_integer' => 'رقم ملف الموظف يجب ان يكون رقما.',

    // Add To Cart Validation

    'user_id' => [
        'required' => 'معرف المستخدم مطلوب.',
        'exists' => 'المستخدم المحدد غير موجود.',
        'integer' => 'معرف المستخدم يجب أن يكون رقماً صحيحاً.',
    ],

    'variant_id' => [
        'required' => 'معرف الخاصية مطلوب.',
        'exists' => 'الخاصية المحددة غير موجودة.',
        'integer' => 'معرف الخاصية يجب أن يكون رقماً صحيحاً.',
    ],

    'quantity' => [
        'required' => 'الكمية مطلوبة.',
        'integer' => 'الكمية يجب أن تكون رقماً صحيحاً.',
        'min' => 'يجب أن تكون الكمية على الأقل 1.',
    ],

    'exceeded_quantity' => 'الكمية المطلوبة تتجاوز الكمية المتوفرة في المخزون.',

    // Delete Cart List Validation

    'cart_id' => [
        'required' => 'معرف السلة مطلوب.',
        'exists' => 'عنصر السلة المحدد غير موجود.',
        'integer' => 'معرف السلة يجب أن يكون رقماً صحيحاً.',
    ],


    // Add To Wishlist Validation

    'product_id' => [
        'required' => 'معرف المنتج مطلوب.',
        'exists' => 'المنتج المحدد غير موجود.',
        'integer' => 'معرف المنتج يجب أن يكون رقماً صحيحاً.',
    ],

    // Delete Wishlist Validation

    'wishlist_id' => [
        'required' => 'معرف قائمة المفضلة مطلوب.',
        'exists' => 'عنصر قائمة المفضلة المحدد غير موجود.',
        'integer' => 'معرف قائمة المفضلة يجب أن يكون رقماً صحيحاً.',
    ],

    // Review Product

    'comment' => [
        'required_without' => 'الرجاء إدخال التقييم أو التعليق.',
        'string' => 'التعليق يجب أن يكون نصاً.',
        'max' => 'يجب ألا يتجاوز التعليق 1000 حرف.',
    ],

    'rating' => [
        'required_without' => 'الرجاء إدخال التقييم أو التعليق.',
        'integer' => 'التقييم يجب أن يكون رقماً صحيحاً.',
        'in' => 'يجب أن تكون قيمة التقييم إحدى القيم التالية: 1، 2، 3، 4، أو 5.',
    ],



    // Place New Order Validation

    'address_id' => [
        'required' => 'معرف العنوان مطلوب.',
        'exists' => 'العنوان المحدد غير موجود.',
        'integer' => 'معرف العنوان يجب أن يكون رقماً صحيحاً.',
    ],

    'shipping_id' => [
        'required' => 'معرف طريقة الشحن مطلوب.',
        'exists' => 'طريقة الشحن المحددة غير موجودة.',
        'integer' => 'معرف طريقة الشحن يجب أن يكون رقماً صحيحاً.',
    ],

    'order_details' => [
        'required' => 'تفاصيل الطلب مطلوبة.',
        'array' => 'تفاصيل الطلب يجب أن تكون مصفوفة.',
        'min' => 'يجب أن يحتوي الطلب على منتج واحد على الأقل.',
    ],

    'unit_price' => [
        'required' => 'سعر الوحدة مطلوب.',
        'numeric' => 'سعر الوحدة يجب أن يكون رقماً.',
        'min' => 'سعر الوحدة يجب أن يكون أكبر من أو يساوي صفر.',
    ],

    'total_price' => [
        'required' => 'السعر الإجمالي مطلوب.',
        'numeric' => 'السعر الإجمالي يجب أن يكون رقماً.',
        'min' => 'السعر الإجمالي يجب أن يكون أكبر من أو يساوي صفر.',
    ],

    // Client Side Messgaes

    'home_page' => 'تم اراجاع تفاصيل الموقع كاملة بنجاح.',
    'get_site_theme' => 'تم جلب إعدادات مظهر الموقع بنجاح.',
    'get_site_media' => 'تم جلب وسائط الموقع بنجاح.',
    'get_products_list' => 'تم جلب قائمة المنتجات بنجاح.',
    'get_product_details' => 'تم جلب تفاصيل المنتج بنجاح.',
    'get_shipping_methods_list' => 'تم جلب قائمة طرق الشحن بنجاح.',
    'get_coupons_list' => 'تم جلب قائمة الكوبونات بنجاح.',
    'review_product' => 'تم إرسال تقييمك بنجاح.',
    'get_user_wishlist' => 'تم جلب قائمة المفضلة الخاصة بالمستخدم بنجاح.',
    'add_to_wishlist' => 'تمت إضافة المنتج إلى قائمة المفضلة بنجاح.',
    'delete_wishlist' => 'تم حذف العنصر من قائمة المفضلة بنجاح.',
    'get_user_cart_list' => 'تم جلب قائمة سلة المستخدم بنجاح.',
    'add_to_cart' => 'تمت إضافة المنتج إلى السلة بنجاح.',
    'update_cart_details' => 'تم تحديث تفاصيل السلة بنجاح.',
    'delete_cart_list' => 'تم حذف عنصر السلة بنجاح.',
    'get_user_orders' => 'تم جلب طلبات المستخدم بنجاح.',
    'add_new_order' => 'تم إنشاء الطلب بنجاح.',
    'cart_not_found' => 'عنصر السلة غير موجود.',



    'data_retrieved' => 'تم جلب البيانات بنجاح.',

    'data_not_found' => 'البيانات غير موجودة.',

    'data_added' => 'تمت إضافة البيانات بنجاح.',

    'data_updated' => 'تم تحديث البيانات بنجاح.',

    'data_deleted' => 'تم حذف البيانات بنجاح.',


// Category Validation

    'category_id_required' => 'رقم القسم مطلوب.',
    'category_id_exists' => 'رقم القسم غير موجود.',
    'category_id_integer' => 'رقم القسم يجب ان يكون رقما.',

    'category_en_name_required' => 'اسم القسم باللغة الانجليزية مطلوب.',
    'category_en_name_unique' => 'اسم القسم باللغة الانجليزية موجود بالفعل.',
    'category_ar_name_required' => 'اسم القسم باللغة العربية مطلوب.',
    'category_ar_name_unique' => 'اسم القسم باللغة العربية موجود بالفعل.',
    'category_image_required' => 'صورة القسم مطلوبة.',
    'category_image_mimes' => 'يجب ان يكون الملف من نوع (jpg, png).',
    'category_image_max' => 'لا يجب ان يتجاوز حجم الملف  5MB.',
    'category_status_in' => 'حالة القسم يجب ان تكون اما 1 او 0 .',

    'add_new_category' => 'تم اضافة القسم (:category_ar_name) بنجاح ',
    'update_category' => 'تم تحديث القسم (:category_ar_name) بنجاح ',
    'delete_category' => 'تم تعطيل القسم (:category_id) بنجاح ',

// Pages Validation

    'page_id_required' => 'رقم الصفحة مطلوب.',
    'page_id_exists' => 'رقم الصفحة غير موجود.',
    'page_id_integer' => 'رقم الصفحة يجب ان يكون رقما.',
    'page_en_name_required' => 'اسم الصفحة باللغة الانجليزية مطلوب.',
    'page_en_name_unique' => 'اسم الصفحة باللغة الانجليزية موجود بالفعل.',
    'page_ar_name_required' => 'اسم الصفحة باللغة العربية مطلوب.',
    'page_ar_name_unique' => 'اسم الصفحة باللغة العربية موجود بالفعل.',
    'page_code_required' => 'الرمز المرجعي للصفحة مطلوب.',
    'page_code_unique' => 'الرمز المرجعي للصفحة موجود بالفعل.',
    'page_status_integer' => 'حالة الصفحة يجب ان تكون رقما.',
    'page_status_in' => 'حالة الصفحة يجب ان تكون اما 1 او 0 .',

    'add_new_page' => 'تم اضافة الصفحة (:page_ar_name) بنجاح ',
    'update_page' => 'تم تحديث الصفحة (:page_ar_name) بنجاح ',
    'delete_page' => 'تم تعطيل الصفحة (:page_id) بنجاح ',


// Features Validation
    'feature_id_required' => 'رقم الميزة مطلوب.',
    'feature_id_exists' => 'رقم الميزة غير موجود.',
    'feature_id_integer' => 'رقم الميزة يجب ان يكون رقما.',
    'feature_en_name_required' => 'اسم الميزة باللغة الانجليزية مطلوب.',
    'feature_en_name_unique' => 'اسم الميزة باللغة الانجليزية موجود بالفعل.',
    'feature_ar_name_required' => 'اسم الميزة باللغة العربية مطلوب.',
    'feature_ar_name_unique' => 'اسم الميزة باللغة العربية موجود بالفعل.',
    'feature_code_required' => 'الرمز المرجعي للميزة مطلوب.',
    'feature_code_unique' => 'الرمز المرجعي للميزة موجود بالفعل.',
    'feature_status_integer' => 'حالة الميزة يجب ان تكون رقما.',
    'feature_status_in' => 'حالة الميزة يجب ان تكون اما 1 او 0 .',
    'feature_type_required' => 'نوع الميزة مطلوب.',
    'feature_type_in' => 'نوع الميزة يجب ان تكون (category,PAGE,ACTION,GLOBAL)  .',
    'feature_is_default_integer' => 'حالة الميزة يجب ان تكون رقما.',
    'feature_is_default_in' => 'حالة الميزة يجب ان تكون اما 1 او 0 .',
    'feature_parent_id_required' => 'معرّف الميزة الأب مطلوب.',
    'feature_parent_id_integer' => 'يجب أن يكون معرّف الميزة الأب رقمًا.',

    'add_new_feature' => 'تم اضافة الميزة (:feature_ar_name) بنجاح ',
    'update_feature' => 'تم تحديث الميزة (:feature_ar_name) بنجاح ',
    'delete_feature' => 'تم تعطيل الميزة (:feature_id) بنجاح ',

    'assign_feature' => 'تم اسناد الميزة (:feature_id) بنجاح ',
    'unassign_feature' => 'تم الغاء اسناد الميزة (:feature_id) بنجاح ',

    'role_already_assigned' => 'لقد تم اسناد الخاصية لهذه الصلاحية من قبل.',
    'employee_already_assigned' => 'لقد تم اسناد الخاصية لهذا الموظف من قبل.',

// Roles Validation
    'role_id_required' => 'رقم الصلاحية مطلوب.',
    'role_id_exists' => 'رقم الصلاحية غير موجود.',
    'role_id_integer' => 'رقم الصلاحية يجب ان يكون رقما.',

    'allow_integer' => 'السماح للموظف يجب ان يكون رقما.',
    'role_array' => 'ارقام الصلاحيات يجب انت ترسل كمصفوفة.',
    'role_min' => 'مصفوفة ارقام الصلاحيات يجب ان تحتوي صلاحية واحد على الاقل.',
    'role_en_name_required' => 'اسم الصلاحية باللغة الانجليزية مطلوب.',
    'role_en_name_unique' => 'اسم الصلاحية باللغة الانجليزية موجود بالفعل.',
    'role_ar_name_required' => 'اسم الصلاحية باللغة العربية مطلوب.',
    'role_ar_name_unique' => 'اسم الصلاحية باللغة العربية موجود بالفعل.',
    'role_code_required' => 'الرمز المرجعي للصلاحية مطلوب.',
    'role_code_unique' => 'الرمز المرجعي للصلاحية موجود بالفعل.',

    'add_new_role' => 'تم اضافة الصلاحية (:role_ar_name) بنجاح ',
    'update_role' => 'تم تحديث الصلاحية (:role_ar_name) بنجاح ',
    'delete_role' => 'تم تعطيل الصلاحية (:role_id) بنجاح ',

    'assign_role' => 'تم تعيين الموظف بنجاح.',
    'unassign_role' => 'تم الغاء تعيين الموظف بنجاح.',
];
