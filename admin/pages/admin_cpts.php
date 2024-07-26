<div class="wrap">
    <?php 

$saved_cpts = get_option('admin_saved_cpts', []);
?>
<div class="special-form">
<?php echo reset_options_form('admin_saved_cpts','reset_cpt_button','Remove Custom Post-Types');?>
</div>
<?php
if(!empty($saved_cpts)){
    echo '<h3>Saved Custom Post-Types</h3>';
$n = 0;
echo '<div class="">';
echo '<table class="wp-list-table widefat fixed striped posts special-form">';
?>
    <tr class="thead">
        <th> &nbsp; # &nbsp;</th>
        <th>Post-type Name </th>
        <th> Post-type</th>
        <th> Supports</th>
        <th> Position</th>
        <!-- <th> </th> -->
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
        <!-- <td><?php //echo display_delete_option_form('admin_saved_cpts', $cpt['post_type_name']);?></td> -->
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
    
    // $saved_cpts = get_option('admin_saved_cpts', []);
    // if (!empty($saved_cpts)) {
    //     foreach ($saved_cpts as $cpt) {
    //         if (isset($cpt['post_type'])) {
    //             create_cpt([
    //                 'post_type' => $cpt['post_type'],
    //                 'post_type_name' => $cpt['post_type_name'],
    //                 'icon' => $cpt['icon'],
    //                 'menu_position' => $cpt['menu_position'],
    //                 // 'parent_menu' => $cpt['parent_menu'],
    //             ]);

    //         } else {
    //             // Handle the case where 'post_type' is not set
    //             // For example, log an error or skip this CPT
    //             error_log('Undefined post_type in $cpt: ' . print_r($cpt, true));
    //         }
    //     }
    // }
    
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