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

function get_compatibility_js_files() {

	$files = array();
	if (PoP_Frontend_ServerUtils::use_minified_files()) {

		$files[] = 'https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js';
		$files[] = 'https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js';
	}
	else {

		$files[] = POPTHEME_WASSUP_URI.'/js/compat/html5shiv.min.js';
		$files[] = POPTHEME_WASSUP_URI.'/js/compat/respond.min.js';
	}

	return $files;
}


/**---------------------------------------------------------------------------------------------------------------
 * Inline styles
 * ---------------------------------------------------------------------------------------------------------------*/
if (!is_admin()) {
	add_action( 'wp_head', 'pop_header_inlinestyles');
}
function pop_header_inlinestyles() {

	if ($inlinestyles = apply_filters('pop_header_inlinestyles:styles', '')) {

		printf('<style type="text/css">%s</style>', $inlinestyles);
	}
}
