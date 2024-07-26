<?php


// new CustomTaxonomy('director', array('post', 'page'));

// Get the serialized option from the database
$admin_saved_taxonomies = get_option('admin_saved_taxonomies');

// print_r($admin_saved_taxonomies);


// Check if the option exists and is not empty
if (!empty($admin_saved_taxonomies)) {
    // Deserialize the option value
     $admin_saved_taxonomies = maybe_unserialize($admin_saved_taxonomies);

    // Check if it's an array
    if (is_array($admin_saved_taxonomies)) {
        // print_r($admin_saved_taxonomies);
        foreach ($admin_saved_taxonomies as $ct) {
            // Check if taxonomy_name and assigned_to are set
            
            if (isset($ct['taxonomy_name']) && isset($ct['assigned_to'])) {
                
                // print_r($ct[$ct['taxonomy_name']]);
                
                $taxonomy_name = strtolower($ct['taxonomy_name']);
                $assign_to = str_replace(' ', '', $ct['assigned_to']); // ct['assigned_to'];
                // $assign_to = array_map('strtolower', $assign_to);
                $assigned_to = explode(",", trim($assign_to)); 
                // print_r($assigned_to);    
                new CustomTaxonomy($taxonomy_name, $assigned_to);

            } else {
                error_log('Undefined custom taxonomy in $ct: ' . print_r($ct, true));
            }
        }
    } else {
        error_log('admin_saved_taxonomies is not an array: ' . print_r($admin_saved_taxonomies, true));
    }
} else {
    error_log('No custom taxonomies found in admin_saved_taxonomies option.');
}

