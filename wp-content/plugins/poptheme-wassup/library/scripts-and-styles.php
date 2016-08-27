<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Scripts and styles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**
 * Register my own admin.css for adding new styles. Adds the function 'add_my_stylesheet' to the wp_enqueue_scripts action.
 */
// Load GD admin css
add_action( 'admin_print_styles', 'add_gd_admin_stylesheet' );	
function add_gd_admin_stylesheet() {

	echo '<link rel="stylesheet" href="' . POPTHEME_WASSUP_URI . '/css/admin.css" type="text/css" />';
}

// Add modified variable as to force Browser to fetch new copy of the style.css file
// add_filter("stylesheet_uri", "gd_stylesheet_uri_add_modified_var", 20);
// function gd_stylesheet_uri_add_modified_var($stylesheet) {

// 	return $stylesheet . "?version=" . pop_version();
// }

