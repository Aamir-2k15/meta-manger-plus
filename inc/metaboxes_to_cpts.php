<?php

// Usage Example
$fields = array(
    // Text Field
    array(
        'id' => 'custom_text',
        'label' => 'Text Field',
        'type' => 'text',
    ),

    // Textarea Field
    array(
        'id' => 'custom_textarea',
        'label' => 'Textarea Field',
        'type' => 'textarea',
    ),

    // Radio Buttons
    array(
        'id' => 'custom_radio',
        'label' => 'Radio Options',
        'type' => 'radio',
        'options' => array(
            'option1' => 'Option 1',
            'option2' => 'Option 2',
        ),
    ),

    // Checkbox
    array(
        'id' => 'custom_checkbox',
        'label' => 'Checkbox Option',
        'type' => 'checkbox',
    ),

    // WYSIWYG Editor
    array(
        'id' => 'custom_wysiwyg',
        'label' => 'WYSIWYG Editor',
        'type' => 'wysiwyg',
    ),

    // File Upload
    array(
        'id' => 'custom_upload',
        'label' => 'File Upload',
        'type' => 'upload',
    ),

    // Select Dropdown
    array(
        'id' => 'custom_select',
        'label' => 'Select Option',
        'type' => 'select',
        'options' => array(
            'option1' => 'Option 1',
            'option2' => 'Option 2',
        ),
    ),

    // Dynamic Select (Posts)
    array(
        'id' => 'custom_dynamic_select',
        'label' => 'Select a Post',
        'type' => 'dynamic_select',
        'select_type' => 'posts',// it can be post types, taxonomies or users
        'post_type' => 'post',
    ),

       
array(
    'id' => 'custom_upload',
    'label' => 'Single File Upload',
    'type' => 'upload',
),


);
 
$difficulty =    [ 
    [
    'id' => 'difficulty',
    'label' => 'Select Option',
    'type' => 'select',
    'options' => ['Easy', 'Medium', 'Hard'],
    ]
];



new Custom_Metabox('custom_metabox_fields', 'Fields', 'page', $fields);
new Custom_Metabox('custom_metabox_difficulty', 'Test Difficulty', 'page', $difficulty);