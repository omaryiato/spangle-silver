<?php
return [

    'login_user_required' => 'Employee file number is required.',
    'login_user_integer' => 'Employee file number must be integer.',

// Categorys Validation
    'category_id_required' => 'Category ID is required.',
    'category_id_exists' => 'Category ID not exist.',
    'category_id_integer' => 'Category ID must be integer.',
    'category_en_name_required' => 'Category english name is required.',
    'category_en_name_unique' => 'Category english name is already exist.',
    'category_ar_name_required' => 'Category arabic name is required.',
    'category_ar_name_unique' => 'Category arabic name is already exist.',
    'category_image_required' => 'Category image File is required.',
    'category_image_mimes' => 'Category image File must be a valid file type (jpg, png).',
    'category_image_max' => 'Category image File size must not exceed 5 MB.',
    'category_status_integer' => 'Category Status must be integer.',
    'category_status_in' => 'Category Status must be (1 or 0).',
    'add_new_category' => 'Category (:category_en_name) added successfully.',
    'update_category' => 'Category (:category_en_name) updated successfully.',
    'delete_category' => 'Category (:category_id) inactive successfully.',

// Pages Validation
    'page_id_required' => 'Page ID is required.',
    'page_id_exists' => 'Page ID not exist.',
    'page_id_integer' => 'Page ID must be integer.',

    'page_en_name_required' => 'Page english name is required.',
    'page_en_name_unique' => 'Page english name is already exist.',
    'page_ar_name_required' => 'Page arabic name is required.',
    'page_ar_name_unique' => 'Page arabic name is already exist.',
    'page_code_required' => 'Page Code is required.',
    'page_code_unique' => 'Page Code is already exist.',
    'page_status_integer' => 'Page Status must be integer.',
    'page_status_in' => 'Page Status must be (1 or 0).',

    'add_new_page' => 'Page (:page_en_name) added successfully.',
    'update_page' => 'Page (:page_en_name) updated successfully.',
    'delete_page' => 'Page (:page_id) inactive successfully.',

// Features Validation
    'feature_id_required' => 'Feature ID is required.',
    'feature_id_exists' => 'Feature ID not exist.',
    'feature_id_integer' => 'Feature ID must be integer.',

    'feature_en_name_required' => 'Feature english name is required.',
    'feature_en_name_unique' => 'Feature english name is already exist.',
    'feature_ar_name_required' => 'Feature arabic name is required.',
    'feature_ar_name_unique' => 'Feature arabic name is already exist.',
    'feature_code_required' => 'Feature Code is required.',
    'feature_code_unique' => 'Feature Code is already exist.',
    'feature_status_integer' => 'Feature Status must be integer.',
    'feature_status_in' => 'Feature Status must be (1 or 0).',
    'feature_type_required' => 'Feature Type is required.',
    'feature_type_in' => 'Feature Status must be (category,PAGE,ACTION,GLOBAL).',
    'feature_is_default_integer' => 'Feature Status must be integer.',
    'feature_is_default_in' => 'Feature Status must be (1 or 0).',
    'feature_parent_id_required' => 'Feature Parent ID is required.',
    'feature_parent_id_integer' => 'Feature Status must be integer.',

    'add_new_feature' => 'Feature (:feature_en_name) added successfully.',
    'update_feature' => 'Feature (:feature_en_name) updated successfully.',
    'delete_feature' => 'Feature (:feature_id) inactive successfully.',

    'assign_feature' => 'Feature (:feature_id) assigned successfully.',
    'unassign_feature' => 'Feature (:feature_id) unassigned successfully.',

    'role_already_assigned' => 'This role already assigned to this feature before.',
    'employee_already_assigned' => 'This employee already assigned to this feature before.',

// Roles Validation
    'role_id_required' => 'Role ID is required.',
    'role_id_exists' => 'Role ID not exist.',
    'role_id_integer' => 'Role ID must be integer.',

    'allow_integer' => 'Role allow must be integer.',

    'role_array' => 'Role IDs must be an array.',
    'role_min' => 'Roles array must contain at least one role.',
    'role_en_name_required' => 'Role english name is required.',
    'role_en_name_unique' => 'Role english name is already exist.',
    'role_ar_name_required' => 'Role arabic name is required.',
    'role_ar_name_unique' => 'Role arabic name is already exist.',
    'role_code_required' => 'Role Code is required.',
    'role_code_unique' => 'Role Code is already exist.',

    'add_new_role' => 'Role (:role_en_name) added successfully.',
    'update_role' => 'Role (:role_en_name) updated successfully.',
    'delete_role' => 'Role (:role_id) inactive successfully.',

    'assign_role' => 'Employee (:role_id) assigned successfully.',
    'unassign_role' => 'Employee (:role_id) unassigned successfully.',
];
