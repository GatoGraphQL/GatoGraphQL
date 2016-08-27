<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Hide the Admin Bar in the website for users (only when having cap 'delete_pages' they are granted access to WP back-end)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function hide_admin_bar_settings() {
?>
	<style type="text/css">
		.show-admin-bar {
			display: none;
		}
	</style>
<?php
} 
function disable_admin_bar() {

	// Comment Leo 12/05/2014: Admin Bar: always hidden, also for admins, because since using Template Manager
	// the links in the bar do not get refreshed after calling a new page
	// if (!user_has_admin_access()) { 
      add_filter( 'show_admin_bar', '__return_false' );
      add_action( 'admin_print_scripts-profile.php', 'hide_admin_bar_settings' );
   // }
}
add_action( 'init', 'disable_admin_bar' , 9 );


/*
 * Function to remove all access to the backend (also edit one's profile) for Subscribers
 */
add_action('admin_init', 'no_mo_dashboard');
function no_mo_dashboard() {

	// For the Contributor role, allow them to upload to Media Library
	// media-upload.php: accessed by JWP6 plugin
	global $pagenow;	
	if( in_array($pagenow, array('async-upload.php', 'media-upload.php'))) {
	
        return;
	}
	
	// doing_ajax: so as to allow them to login (with simplemodal-login)
	// or to subscribe to newsletter in Right Navigation widget
	if (user_has_admin_access() || doing_ajax()) {
	
		return;
	} 

	// Otherwise, send them back home
	wp_redirect(home_url()); exit;
}

/**
 * Remove access to wp-login.php
 */
add_action('gd_wp_login', 'gd_wp_login_redirect');
function gd_wp_login_redirect() {

	wp_redirect(wp_login_url()); exit;
}

/* End of file hide_admin_bar.php */
/* Location: ./admin/hide_admin_bar.php */
