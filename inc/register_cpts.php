<?php

// Register Custom Post Types
function register_custom_post_types() {

    $saved_cpts = get_option('admin_saved_cpts', []);
if (!empty($saved_cpts) && is_array($saved_cpts)) {
    foreach ($saved_cpts as $cpt) {
        if (isset($cpt['post_type'])) {
            $menu_position = isset($cpt['menu_position']) ? (int)$cpt['menu_position'] : null;
            create_cpt([
                'post_type' => $cpt['post_type'],
                'post_type_name' => $cpt['post_type_name'],
                'icon' => $cpt['icon'],
                'menu_position' => $menu_position,
                // 'parent_menu' => $cpt['parent_menu'],
            ]);

        } else {
            // Handle the case where 'post_type' is not set
            // For example, log an error or skip this CPT
            error_log('Undefined post_type in $cpt: ' . print_r($cpt, true));
        }
    }
}


// create_cpt(array(
//     'post_type' => 'book',
//     'post_type_name' => 'Book',
//     'icon' => 'book',
//     'menu_position' => 20,
//     // 'parent_menu' => 'edit.php?post_type=library'
// ));
}
 add_action( 'init', 'register_custom_post_types' );