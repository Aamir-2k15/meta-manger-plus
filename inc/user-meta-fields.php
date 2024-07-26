<?php

// $fields = array(
//     array(
//         'type' => 'text',
//         'id' => 'user_phone',
//         'name' => 'user_phone',
//         'label' => 'Phone',
//         'placeholder' => 'Enter phone number',
//         'custom_attributes' => array('required' => 'required')
//     ),
//     array(
//         'type' => 'email',
//         'id' => 'user_email_2',
//         'name' => 'user_email_2',
//         'label' => 'Email',
//         'placeholder' => 'Enter email address'
//     ),
//     array(
//         'type' => 'number',
//         'id' => 'user_age',
//         'name' => 'user_age',
//         'label' => 'Age',
//         'placeholder' => 'Enter your age'
//     ),
//     array(
//         'type' => 'textarea',
//         'id' => 'user_bio',
//         'name' => 'user_bio',
//         'label' => 'Biography',
//         'placeholder' => 'Enter biography'
//     ),
//     array(
//         'type' => 'radio',
//         'id' => 'user_gender',
//         'name' => 'user_gender',
//         'label' => 'Gender',
//         'options' => array('male' => 'Male', 'female' => 'Female')
//     ),
//     array(
//         'type' => 'checkbox',
//         'id' => 'user_agree',
//         'name' => 'user_agree',
//         'label' => 'Agree to terms',
//         'description' => 'Check to agree to terms and conditions'
//     ),
//     array(
//         'type' => 'checkbox_multiselect',
//         'id' => 'user_hobbies',
//         'name' => 'user_hobbies',
//         'label' => 'Hobbies',
//         'options' => array('reading' => 'Reading', 'traveling' => 'Traveling', 'gaming' => 'Gaming')
//     ),
//     array(
//         'type' => 'select',
//         'id' => 'user_country',
//         'name' => 'user_country',
//         'label' => 'Country',
//         'options' => array('us' => 'United States', 'uk' => 'United Kingdom', 'ca' => 'Canada')
//     ),
//     array(
//         'type' => 'colorpicker',
//         'id' => 'user_favorite_color',
//         'name' => 'user_favorite_color',
//         'label' => 'Favorite Color',
//         'placeholder' => 'Choose your favorite color'
//     ),
//     array(
//         'type' => 'upload',
//         'id' => 'user_profile_picture',
//         'name' => 'user_profile_picture',
//         'label' => 'Profile Picture',
//         'placeholder' => 'Upload your profile picture'
//     ),
//     array(
//         'type' => 'date',
//         'id' => 'user_birthdate',
//         'name' => 'user_birthdate',
//         'label' => 'Birthdate',
//         'placeholder' => 'Select your birthdate'
//     ),
// );

$admin_usermeta_fields = get_option('admin_usermeta_fields', []); 
$user_fields = [];

if (!empty($admin_usermeta_fields)) {
    foreach ($admin_usermeta_fields as $field) {
        $user_fields[] = [
            'type'        => $field['type'],
            'id'          => $field['name'],
            'name'        => $field['name'],
            'label'       => convertToFormat($field['name'], 'label'),
            'options'     => !empty($field['options']) ? $field['options'] : [],
            'placeholder' => convertToFormat($field['name'], 'label'),
        ];
    }
}

$user_meta_admin_fields = new UserMeta_Admin_Fields();
$user_meta_admin_fields->add_fields($user_fields);
