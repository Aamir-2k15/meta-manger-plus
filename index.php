<?php
/*
Plugin Name: Meta Manager Plus
Description: Helpful in creating Custom Metaboxes, Usermeta or Custom Admin Panel. Bugfixes and additional features will be added in next versions.
Version: 1.0 Beta
Author: Aamir
Author URI: 
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'inc/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/lib/class-wp-form-builder.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/lib/class-custom-admin-page.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/admin_page.php';


// Include the class for custom post type creator
require_once plugin_dir_path( __FILE__ ) . 'inc/lib/cpt-creator.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/register_cpts.php';

require_once plugin_dir_path( __FILE__ ) . 'inc/lib/class-custom-taxonomy.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/register_tax.php';





 require_once plugin_dir_path( __FILE__ ) . 'inc/lib/class-custom-metabox.php'; // 

 require_once plugin_dir_path( __FILE__ ) . 'inc/metaboxes_to_cpts.php';



/****************************************|CUSTOM USERMETA|*************************************/

require_once plugin_dir_path( __FILE__ ) . 'inc/lib/class-add-admin-user-meta.php';  
require_once plugin_dir_path( __FILE__ ) . 'inc/user-meta-fields.php';  