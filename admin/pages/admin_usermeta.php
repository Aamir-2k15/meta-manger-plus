<div class="wrap"> 
    <!-- . -->
<div class="special-form">
    <?php echo reset_options_form('admin_usermeta_fields','reset_admin_usermeta_button','Remove Admin Usermeta Fields');?>
</div>
    <div class="special-form">
        <h2>Add Admin Usermeta Field</h2>
        <?php     $types =  field_types();    ?>
        <form method="post" action="" id="save-admin-usermeta-fields">
            <?php //wp_nonce_field('admin_usermeta_fields', 'admin_usermeta_fields_nonce'); ?>
            <?php 
            if (isset($_POST['admin_usermeta_fields_button'])) {
                
                // if (check_admin_referer('admin_usermeta_fields', 'admin_usermeta_fields_nonce')) {
                    $field_name =  (convertToFormat($_POST['field_name'], 'name'));
                    $field_type =  ($_POST['field_type']);
                    $options_input =  ($_POST['options']);
                    $option_types = ['radio', 'checkbox', 'checkbox_multiselect', 'select'];
                    // Check if fields are empty
                    if (empty($field_name) || empty($field_type)) {
                        echo '<div class="error"><p>Both fields are required.</p></div>';
                    } else {
                        unset($_POST['admin_usermeta_fields_button']);
                        $options = get_option('admin_usermeta_fields', []);
                        if (!in_array($field_name, array_column($options, 'name'))) {
                            
                            if (in_array($field_type, $option_types)) {
                                // Convert comma-separated values into an associative array
                                $options_array = array_map('trim', explode(',', $options_input));
                                $associative_options_array = [];

                                foreach ($options_array as $option) {
                                    if ($uppercase_key == 'admin_usermeta_fields_button ') {
                                        continue;
                                    }
                                    $uppercase_key = ucwords($option);
                                    $lowercase_value = strtolower($option);
                                    $associative_options_array[$uppercase_key] = $lowercase_value;
                                }

                                // Add the associative options array to the field data
                                $options[] = ['name' => $field_name, 'type' => $field_type, 'options' => $associative_options_array];
                            }else{
                                $options[] = ['name' =>  $field_name, 'type' => $field_type, 'options' => $options_input];
                            }

                            update_option('admin_usermeta_fields', $options);
                            echo '<div class="updated"><p>Field saved.</p></div>';
                        } else {
                            echo '<div class="error"><p>Field name already exists.</p></div>';
                        }
                    }
                // }
            }            
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="field_name">Field Name</label></th>
                    <td><input type="text" id="field_name" name="field_name"
                            class="regular-text wp-form-builder-field" /></td>
                    <!-- </tr>
                <tr valign="top"> -->
                    <th scope="row"><label for="field_type">Field Type</label></th>
                    <td>
                        <select id="field_type" name="field_type" class=" wp-form-builder-field">
                            <?php foreach ($types as $key => $value) {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top" colspan="2">
                    <th scope="row"><label for="options">Options</label></th>
                    <td><input type="text" id="options" name="options" class="regular-text wp-form-builder-field" />
                    </td>
                </tr>
            </table>

            <?php submit_button('Save Admin Usermeta Field', 'primary', 'admin_usermeta_fields_button'); ?>

            <strong>Note: <em>For Dynamic Fields, Options Field string like:
                    <code>all_users</code>,
                    <code>all_custom_post_types</code>,
                    <code>all_post_types</code>,
                    <code>all_taxonomies</code>,
                    <code>all_posts</code>,
                    <code>all_terms</code>,
                    <code>all_pages</code>,
                    <code>all_post_type_titles</code> <br />
                    For ['radio', 'checkbox', 'checkbox_multiselect', 'select',] Use comma seperated values in options
                    field.
                </em></strong>
        </form>
    </div>
    <!-- ./ -->

    <div class="admin-settings">

        <?php 
            if (isset($_POST['admin_usermeta_fields_button'])) {
                    // Get the existing options from the database
                    $admin_settings = get_option('admin_usermeta_fields', []);
                    
                    // Sanitize and update settings from POST data
                    foreach ($_POST as $key => $value) {
                        // Sanitize the value based on expected data type
                        $admin_settings[$key] = ($value);
                    }
                    // Update the options in the database
                    update_option('admin_usermeta_fields', $admin_settings);
                
                    // Display a success message
                    echo '<div class="updated"><p>Field saved.</p></div>';                            
            }            
            ?>
        <?php

$admin_usermeta_fields = get_option('admin_usermeta_fields', []); 
// print_r($admin_usermeta_fields);
?>
        <table class="wp-list-table widefat fixed striped posts">
            <thead>
                <tr>
                    <th style="width: 25%">Field Name</th>
                    <th style="width: 25%">Field Type</th>
                    <th style="width: 25%">Options</th>
                    <th style="width: 25%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
    if(!empty($admin_usermeta_fields)){  
    foreach ($admin_usermeta_fields as $field ){ 
        // print_r($field['type']);
        ?>
                <tr>
                    <td style="width: 25%"><?php echo convertToFormat($field['name'], 'label');?></td>
                    <td style="width: 25%"><?php echo $field['type'];?></td>
                    <td style="width: 25%"><?php echo $field['options'];?></td>
                    <td style="width: 25%"><?php echo display_delete_option_form('admin_usermeta_fields', $field['name']);?></td>
                </tr>
                <?php               
    }    
    }  
?></tbody>
</table>


    </div>
</div> <!-- Ends wrap -->