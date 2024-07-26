<div class="wrap">
    <!-- . -->
    <?php     $types =  field_types();    ?>

    <div class="special-form">
        <?php echo reset_options_form('saved_metabox','reset_metabox_button','Remove Metaboxes');?>
    </div>

    <form method="post" action="" id="save-metabox">
        <div class="special-form">


            <table style="width:70%;float:left;">
                <tr valign="top" colspan="2">
                    <th scope="row"><label for="metabox_name"> Metabox Name</label></th>
                    <td scope="row"><input type="text" name="metabox_name" id="metabox_name"></td>
                    <th scope="row"><label for="post_type">Add Metabox to</label></th>
                    <td>
                        <?php 
$all_post_types = get_post_types();
$excluded_post_types = ['wpcf7_contact_form','wp_font_face','wp_font_family','attachment','revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block', 'wp_template', 'wp_template_part', 'wp_global_styles', 'wp_navigation', 'wp_area']; // Add other post types you want to exclude here

?>
                        <select name="post_type" id="post_type">
                            <?php foreach ($all_post_types as $key => $value) { 
if (!in_array($key, $excluded_post_types)) { ?>
                            <option value="<?php echo $key; ?>"><?php echo ucwords($value); ?></option>
                            <?php } 
}?>
                        </select>

                    </td>
                </tr>
            </table>
            <a href="javascript:void(0)" class="add-clone-button button">Add New Field to Metabox</a>
        </div>
        <div class="parent-container">
            <p><strong>Note: <em>For Dynamic Fields, Options Field string like:
                        <code>all_users</code>,
                        <code>all_custom_post_types</code>,
                        <code>all_post_types</code>,
                        <code>all_taxonomies</code>,
                        <code>all_posts</code>,
                        <code>all_terms</code>,
                        <code>all_pages</code>,
                        <code>all_post_type_titles</code> <br />
                        For ['radio', 'checkbox', 'checkbox_multiselect', 'select',] Use comma seperated values in
                        options
                        field.
                    </em></strong></p>

            <div class="special-form to-clone">
                <h2>Add Field to Metabox</h2>



                <?php
// if (isset($_POST['save_metabox_button'])) {
//     $metabox_name = sanitize_text_field($_POST['metabox_name']);
//     $post_type = sanitize_text_field($_POST['post_type']);
    
//     pre_dump($_POST);
// }
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





            </div>
            <!-- ./ -->




        </div>

        <?php submit_button('Save Metabox Fields', 'primary', 'save_metabox_button'); ?>
    </form>
</div> <!-- Ends wrap -->

<script>
function clone_remove_html(to_clone, parent_container_class) {
    var parentContainer = document.querySelector('.' + parent_container_class);

    // Create "Add" button once inside the parent container
    var addButton = document.querySelector('.add-clone-button');

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

<?php
/*
// Retrieve saved metaboxes from the wp_options table
$saved_metaboxes = get_option('saved_metaboxes', []);

if (!empty($saved_metaboxes)) {
    echo '<table border="1" cellspacing="0" cellpadding="5" class="wp-list-table widefat fixed striped posts special-form">';
    echo '<tr><th>Metabox Name</th><th>Post Type</th><th>Field Name</th><th>Field Type</th><th>Options</th></tr>';

    // Loop through each saved metabox
    foreach ($saved_metaboxes as $metabox) {
        $metabox_name = $metabox['name'];
        $post_type = $metabox['post_type'];
        $fields = $metabox['fields'];

        // Loop through each field in the metabox
        foreach ($fields as $field) {
            $field_name = $field['name'];
            $field_type = $field['type'];
            $options = $field['options'];

            // Convert options array to a string for display
            if (is_array($options)) {
                $options_display = '';
                foreach ($options as $key => $value) {
                    $options_display .= "$key: $value<br>";
                }
            } else {
                $options_display = $options;
            }

            echo "<tr>
                    <td>{$metabox_name}</td>
                    <td>{$post_type}</td>
                    <td>{$field_name}</td>
                    <td>{$field_type}</td>
                    <td>{$options_display}</td>
                </tr>";
        }
    }

    echo '</table>';
} else {
    echo '<p>No metaboxes found.</p>';
}
*/