<?php
    add_action('admin_head', function(){
        ?>
<style>
.special-form {
    /* width: 80% !important; */
    margin: 0 auto;
    background: #fff;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 5px #ccc;
    margin-top: 20px;
}
.special-form .wp-form-builder-field {
    width: 100% !important;
}
.wrap form {
    width: 100% !important;
}

.wp-form-builder-field {
    padding: 3px 5px !important;
    width: 72% !important;
    max-width: 73%;
}
</style>
<?php
    }); 
function main_page_content() {
    ?>
<div class="wrap">
    <h1>Main Page</h1>
    <p>Welcome to the main page!</p>
    <!-- . -->
    <?php
    $types = [
        'text' => 'Text',
        'email' => 'Email',
        'number' => 'Number',
        'textarea' => 'Textarea',
        'wysiwyg' => 'Wysiwyg',
        'radio' => 'Radio',
        'checkbox' => 'Checkbox',
        'checkbox_multiselect' => 'Checkbox Multiselect',
        'select' => 'Select',
        'colorpicker' => 'Colorpicker',
        'upload' => 'Upload',
        'date' => 'Date',
        'dynamic_select' => 'Dynamic Select'
    ];
    ?>
    <div class="special-form">
        <form method="post" action="" style="display:inline;" id="reset_form">
            <?php 

                if (isset($_POST['reset_admin_settings_button'])) {
                    // Get the existing options from the database
                    $admin_settings = get_option('admin_settings', []);
                    $admin_fields = get_option('admin_fields', []);
                
                    // Update the options in the database
                    delete_option('admin_settings', $admin_settings);
                    delete_option('admin_fields', $admin_fields);
                
                    // Display a success message
                    echo '<div class="updated"><p>Fields Reset</p></div>';
                }                             
            ?>
            <?php submit_button('Reset & Remove Admin Settings & Fields', 'primary', 'reset_admin_settings_button'); ?>
        </form>
    </div>
    <div class="special-form">
        <h2>Add Admin Field</h2>

        <form method="post" action="" id="save-fields">
            <?php wp_nonce_field('admin_fields', 'admin_fields_nonce'); ?>
            <?php 
            if (isset($_POST['admin_fields_button'])) {
                
                if (check_admin_referer('admin_fields', 'admin_fields_nonce')) {
                    $field_name =  (convertToFormat($_POST['field_name'], 'name'));
                    $field_type =  ($_POST['field_type']);
                    $options_input =  ($_POST['options']);
                    $option_types = ['radio', 'checkbox', 'checkbox_multiselect', 'select'];
                  
                    // Check if fields are empty
                    if (empty($field_name) || empty($field_type)) {
                        echo '<div class="error"><p>Both fields are required.</p></div>';
                    } else {
                        $options = get_option('admin_fields', []);
                        if (!in_array($field_name, array_column($options, 'name'))) {
                            
                            if (in_array($field_type, $option_types)) {
                                // Convert comma-separated values into an associative array
                                $options_array = array_map('trim', explode(',', $options_input));
                                $associative_options_array = [];

                                foreach ($options_array as $option) {
                                    $uppercase_key = ucwords($option);
                                    $lowercase_value = strtolower($option);
                                    $associative_options_array[$uppercase_key] = $lowercase_value;
                                }

                                // Add the associative options array to the field data
                                $options[] = ['name' => $field_name, 'type' => $field_type, 'options' => $associative_options_array];
                            }else{
                                $options[] = ['name' =>  $field_name, 'type' => $field_type, 'options' => $options_input];
                            }
                            update_option('admin_fields', $options);
                            echo '<div class="updated"><p>Field saved.</p></div>';
                        } else {
                            echo '<div class="error"><p>Field name already exists.</p></div>';
                        }
                    }
                }
            }            
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="field_name">Field Name</label></th>
                    <td><input type="text" id="field_name" name="field_name" class="regular-text wp-form-builder-field" /></td>
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
                    <td><input type="text" id="options" name="options" class="regular-text wp-form-builder-field" /></td>
                </tr>
            </table>

            <?php submit_button('Save Admin Field', 'primary', 'admin_fields_button'); ?>

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

        <form method="post" action="" id="save-admin-settings">

            <?php 
            if (isset($_POST['save_admin_settings_button'])) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Get the existing options from the database
                    $admin_settings = get_option('admin_settings', []);
                    
                    // Sanitize and update settings from POST data
                    foreach ($_POST as $key => $value) {
                        // Sanitize the value based on expected data type
                        $admin_settings[$key] = ($value);
                    }
                    // Update the options in the database
                    update_option('admin_settings', $admin_settings);
                
                    // Display a success message
                    echo '<div class="updated"><p>Field saved.</p></div>';
                }                             
            }            
            ?>
            <?php

$saved_fields = get_option('admin_fields', []);

$saved_settings = get_option('admin_settings', []);

if (class_exists('WpFormBuilder')) {

     $fb = new WpFormBuilder;
    if(!empty($saved_fields)){ 
         wp_nonce_field('save_admin_settings', 'save_admin_settings_nonce'); 
    foreach ($saved_fields as $field){ 
// pre_dump($field);
        $type = $field['type'];
        $label =  convertToFormat($field['name'], 'label');
        $name =  convertToFormat($field['name'], 'name');
        $id =  convertToFormat($field['name'], 'id');
        $option_types = ['radio', 'checkbox', 'checkbox_multiselect', 'select', 'dynamic_select'];
        if (in_array($type, $option_types)) {
            $options = $field['options'];
        }
        else {
            $options = false;
        }
             $fb->field([
                'label'=> $label,
                'name' => $name,
                'type' => $type,
                'id' =>  $id,
                'options' =>  $options,
                'value' => !empty($saved_settings[$name]) ? $saved_settings[$name] : '', 
                'description'=> '<strong>Shortcode: </strong><code>['.$name.']</code>',
            ]);      
        }
    
    }  
    $fb->render();
} 
?>
            <?php submit_button('Save Admin Settings', 'primary', 'save_admin_settings_button'); ?>
        </form>

    </div>
</div> <!-- Ends wrap -->
<?php
}

/****
 * 
 * 
 * 
 * 
 * 
 * SUB PAGE
 * 
 * 
 * 
 * 
 * ****/


function subpage_content() {
    echo '<h1>Subpage</h1>';
    echo '<p>Welcome to the subpage!</p>';
    // 
?>
<div class="wrap">
    <?php 

$saved_cpts = get_option('admin_saved_cpts', []);
?>
<div class="special-form">
<form method="post" action="" style="display:inline;" id="remove_cpt">
    <?php 

        if (isset($_POST['reset_cpt_button'])) {
            // Get the existing options from the database

        
            // Update the options in the database
            delete_option('admin_saved_cpts'); 
            echo '<script>customRedirect("admin.php?page=add-cpts")</script>';
        }                             
    ?>
    <?php submit_button('Remove Custom Post-Types', 'primary', 'reset_cpt_button'); ?>
</form>
</div>
<?php
if(!empty($saved_cpts)){
    echo '<h3>Saved Custom Post-Types</h3>';
$n = 0;
echo '<div class="special-form">';
echo '<table class="wp-list-table widefat fixed striped posts">';
?>
    <tr class="thead">
        <th> &nbsp; # &nbsp;</th>
        <th>Post-type Name </th>
        <th> Post-type</th>
        <th> Supports</th>
        <th> Position</th>
        <!-- <th> Parent</th> -->
    </tr>
    <?php
foreach($saved_cpts as $cpt){ 
    $n++;
  ?>
    <tr>
        <td><?php echo $n; ?></td>
        <td><?php echo !empty($cpt['post_type_name']) ? $cpt['post_type_name'] : '';?></td>
        <td><?php echo !empty($cpt['post_type']) ? $cpt['post_type'] : '';?></td>
        <td><?php echo  'title, editor, thumbnail, custom-fields';?></td>
        <td><?php echo !empty($cpt['menu_position']) ? $cpt['menu_position'] : '';?></td>
        <!-- <td><?php //echo !empty($cpt['parent_menu']) ? $cpt['parent_menu'] : '';?></td> -->
    </tr>
    <?php

}
echo '</table>';
echo '</div>';
}else{
    echo '<div class="error"><p>No Saved CPTs.</p></div>';
}
    
    ?>
    <div class="admin-settings">

    <?php
    
    $saved_cpts = get_option('admin_saved_cpts', []);
    if (!empty($saved_cpts)) {
        foreach ($saved_cpts as $cpt) {
            if (isset($cpt['post_type'])) {
                create_cpt([
                    'post_type' => $cpt['post_type'],
                    'post_type_name' => $cpt['post_type_name'],
                    'icon' => $cpt['icon'],
                    'menu_position' => $cpt['menu_position'],
                    // 'parent_menu' => $cpt['parent_menu'],
                ]);

            } else {
                // Handle the case where 'post_type' is not set
                // For example, log an error or skip this CPT
                error_log('Undefined post_type in $cpt: ' . print_r($cpt, true));
            }
        }
    }
    
    ?>
        <?php 
if (isset($_POST['add_custom_post_type_button'])) {
    // Verify nonce
 
    // Get the existing options from the database
    $admin_saved_cpts = get_option('admin_saved_cpts', []);

    @$post_type =  ($_POST['post_type']);

    // Create an array to hold the new CPT data
    $new_cpt = [];

    foreach ($_POST as $key => $value) {
        // Sanitize the value based on expected data type
        if ($key == 'add_custom_post_type_button') {
            continue;
        }
        $new_cpt[$key] = ($value);
    }

    if (in_array($post_type, $admin_saved_cpts)) {
        echo '<div class="error"><p>Post-type already exists.</p></div>';
    } else {
        $admin_saved_cpts[$post_type] = $new_cpt;
        update_option('admin_saved_cpts', $admin_saved_cpts);
        echo '<div class="updated"><p>Custom Post-type created.</p></div>';
        echo '<script>customRedirect("admin.php?page=add-cpts")</script>';
    }
}

?>

        <form method="post" action="" id="save-cpt">
            <?php 
 
    $form_fields = new WpFormBuilder();
$create_cpt_array = ['post_type','post_type_name','icon','menu_position'];
foreach ($create_cpt_array as $field_name):
    $form_fields->field([
        'name' => $field_name,
        'type' => 'text',
        'label' => ucwords(str_replace('_', ' ', $field_name)),
        'id' => $field_name, 
        'value' => '',
        'description' => '',
    ]);
endforeach;    
    $form_fields->render();
    ?>
            <?php submit_button('Add Custom Post Type', 'primary', 'add_custom_post_type_button'); ?>
        </form>
    </div>
</div>
<?php    
 //create_cpt('book', 'Book', 'book', array('title', 'editor', 'thumbnail', 'excerpt'), array('menu_position' => 20));


   /*?>
<div class="parent-container">
    <h1><button class="add button-primary">Add New Section</button></h1>
    <div class="to-clone"></div>
    <script>
    function clone_remove_html(to_clone, parent_container_class) {
        var parentContainer = document.querySelector('.' + parent_container_class);

        // Create "Add" button once inside the parent container
        var addButton = parentContainer.querySelector('.add');

        addButton.addEventListener('click', function() {
            var newContainer = parentContainer.querySelector('.' + to_clone).cloneNode(true);

            // Clear values for all input fields, textareas, and selects
            var fields = newContainer.querySelectorAll('input, textarea, select');
            fields.forEach(function(field) {
                if (field.type === 'checkbox' || field.type === 'radio') {
                    field.checked = false;
                } else {
                    field.value = "";
                }
            });

            // Remove previous remove button from the new container, if exists
            var existingRemoveButton = newContainer.querySelector('.removeButton');
            if (existingRemoveButton) {
                existingRemoveButton.remove();
            }

            // Create and append new remove button
            var removeButton = document.createElement('button');
            removeButton.textContent = 'Remove this section';
            removeButton.classList.add('removeButton');
            removeButton.addEventListener('click', function() {
                newContainer.remove();
            });
            newContainer.appendChild(removeButton);

            // Append cloned container to its parent
            parentContainer.appendChild(newContainer);
        });

        // Add "Remove" buttons to existing containers, except the first one
        var containers = parentContainer.querySelectorAll('.' + to_clone);
        containers.forEach(function(container, index) {
            if (index > 0) {
                var removeButton = document.createElement('button');
                removeButton.textContent = 'Remove this section';
                removeButton.classList.add('removeButton');
                removeButton.addEventListener('click', function() {
                    container.remove();
                });
                container.appendChild(removeButton);
            }
        });
    }

    // Call the function with the class name of the section to clone and parent container class
    clone_remove_html('to-clone', 'parent-container');
    </script>
    <?php */
}

$args = [
    'page_title' => 'Main Page',
    'menu_title' => 'Main Menu',
    'capability' => 'manage_options',
    'menu_slug' => 'main_page',
    'callback' => 'main_page_content',
];

$main_page = new CustomAdminPage($args);
$main_page->add_subpage('Add CPTs', 'add-cpts', 'subpage_content');