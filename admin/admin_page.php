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
function add_admin_settings() {
    ?>
<?php  
$path = 'pages/admin_settings.php';
require_once $path; 
?>
<?php
}

function add_cpt() {
    echo '<h1>Add Custom Post-Types</h1>'; 
?>
<?php require_once 'pages/admin_cpts.php';?>
<?php
}
function add_taxonomies() {
    echo '<h1>Add Custom Taxonomy</h1>'; 
?>
<?php require_once 'pages/admin_taxonomies.php';?>
<?php
}


function add_admin_usermeta() {
    echo '<h1>Add Admin Usermeta Fields</h1>'; 
?>
<?php require_once 'pages/admin_usermeta.php';?>
<?php
}

 
function add_admin_postmeta() {
    echo '<h1>Add Admin Postmeta/Metabox Fields</h1>'; 
?>
<?php require_once 'pages/admin_postmeta.php';?>

<?php
}


$args = [
    'page_title' => 'Main Page',
    'menu_title' => 'Main Menu',
    'capability' => 'manage_options',
    'menu_slug' => 'main_page',
    'callback' => 'add_admin_settings',
];

$main_page = new CustomAdminPage($args);
$main_page->add_subpage('Add CPTs', 'add-cpts', 'add_cpt');
$main_page->add_subpage('Add Taxonomies', 'add-taxonomies', 'add_taxonomies');
$main_page->add_subpage('Add Usermeta Fields', 'add-admin-usermeta', 'add_admin_usermeta');
$main_page->add_subpage('Add Postmeta', 'add-metabox', 'add_admin_postmeta');
