<?php
return [


    'login_user_required' => 'رقم ملف الموظف مطلوب.',
    'login_user_integer' => 'رقم ملف الموظف يجب ان يكون رقما.',

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
