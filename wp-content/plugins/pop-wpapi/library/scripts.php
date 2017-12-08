<?php

/**---------------------------------------------------------------------------------------------------------------
 * scripts.php
 * ---------------------------------------------------------------------------------------------------------------*/
// Remove all the scripts
if (PoP_Frontend_ServerUtils::disable_js()) {
	add_action('wp_print_scripts', 'pop_wpapi_remove_all_scripts', PHP_INT_MAX);

	// Avoid to add scripts in the footer
	remove_action( 'wp_footer',           'wp_print_footer_scripts',         20    );
	remove_action( 'wp_print_footer_scripts', '_wp_footer_scripts'                 );

	// Do not add the Media templates
	add_action('wp_enqueue_media', 'pop_wpapi_remove_media_templates');
}
function pop_wpapi_remove_all_scripts() {
    
    global $wp_scripts;
    $wp_scripts->queue = array();
}
function pop_wpapi_remove_media_templates() {
    
    remove_action( 'wp_footer', 'wp_print_media_templates' );
}
