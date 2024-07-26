<div class="wrap">
    <?php 

$saved_taxonomies = get_option('admin_saved_taxonomies', []);
?>
<div class="special-form">
    <?php echo reset_options_form('admin_saved_taxonomies','reset_taxonomies_button','Remove Custom Taxonomies');?>
</div>
<?php
if(!empty($saved_taxonomies)){
    echo '<h3>Saved Custom Taxonomies</h3>';
$n = 0;
echo '<div class="">';
echo '<table class="wp-list-table widefat fixed striped posts special-form">';
?>
    <tr class="thead">
        <th> &nbsp; # &nbsp;</th> 
        <th> Taxonomy</th>
        <th> Belongs to</th> 
    </tr>
    <?php
foreach($saved_taxonomies as $ct){ 
    $n++;
  ?>
    <tr>
        <td><?php echo $n; ?></td> 
        <td><?php echo !empty($ct['taxonomy_name']) ? $ct['taxonomy_name'] : '';?></td> 
        <td><?php echo !empty($ct['assigned_to'])?  $ct['assigned_to'] : ''; ?></td>
    </tr>
    <?php

}
echo '</table>';
echo '</div>';
}else{
    echo '<div class="error"><p>No Saved Custom Taxonomies.</p></div>';
}
    
    ?>
    <div class="admin-settings">
        <?php 
if (isset($_POST['add_custom_taxonomy_button'])) {
    // Verify nonce
 
    // Get the existing options from the database
    $admin_saved_taxonomies = get_option('admin_saved_taxonomies', []);

    @$taxonomy_name =  ($_POST['taxonomy_name']);
    @$assign_to =  ($_POST['assign_to']);

    // Create an array to hold the new ct data
    $new_ct = [];
    
    foreach ($_POST as $key => $value) {
        // Sanitize the value based on expected data type
        if ($key == 'add_custom_taxonomy_button') {
            continue;
        }
        // print_r($value);
        $new_ct[$key] =  $value ;
    }

    if (in_array($taxonomy_name, $admin_saved_taxonomies)) {
        echo '<div class="error"><p>Taxonomy already exists.</p></div>';
    } else {
        $admin_saved_taxonomies[$taxonomy_name] = $new_ct;
        update_option('admin_saved_taxonomies', $admin_saved_taxonomies);
        echo '<div class="updated"><p>Custom Taxonomy created.</p></div>';
    }
}

?> <form method="post" action="" id="save-ct">
            <?php 
 
    $form_fields = new WpFormBuilder();

 
$form_fields->field([
    'name' => 'taxonomy_name',
    'type' => 'text',
    'label' => 'Taxonomy Name',
    'id' => 'taxonomy_name', 
    'value' => '',
    'description' => '',
]);

// $form_fields->field([
//     'type'    => 'dynamic_multiselect',
//     'label'   => 'Assigned to',
//     'name'    => 'assigned_to',
//     'id'      => 'assigned_to',
//     'options' => 'all_post_types'
// ]);


$form_fields->field([
    'type'    => 'text',
    'label'   => 'Assigned to',
    'name'    => 'assigned_to',
    'id'      => 'assigned_to', 
]);

    $form_fields->render();
    ?>
    <div><strong>Note:</strong>Enter comma, seperated values to assign to multiple post types.</div>
            <?php submit_button('Add Custom Taxonomy', 'primary', 'add_custom_taxonomy_button'); ?>
        </form>
    </div>
</div>
<?php    
  