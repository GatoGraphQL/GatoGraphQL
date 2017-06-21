<?php

define ('GD_WSL_USER_ATTRIBUTE', 'wsluser');

/**---------------------------------------------------------------------------------------------------------------
 *
 * Wordpress Social Login Plugin functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function get_wsl_pop_provider($user_id = null) {

	if (is_null($user_id)) {
		$vars = GD_TemplateManager_Utils::get_vars();
		$user_id = $vars['global-state']['current-user-id']/*get_current_user_id()*/;
	}

	return get_user_meta($user_id, 'wsl_current_provider', true);
}

function gd_wsl_is_wsl_user($user_id = null) {

	$provider = get_wsl_pop_provider($user_id);
	return $provider != null;
}

/**---------------------------------------------------------------------------------------------------------------
 * User Attributes
 * ---------------------------------------------------------------------------------------------------------------*/
// Add a class to the body to identify the user as WSL, to hide the "Change Password" link
add_filter('gd_user_attributes', 'gd_wsl_user_attributes');
function gd_wsl_user_attributes($userattributes) {

	$userattributes[] = GD_WSL_USER_ATTRIBUTE;
	return $userattributes;
}

add_filter('gd_get_userattributes', 'gd_wsl_get_userattributes', 10, 2);
function gd_wsl_get_userattributes($userattributes, $user_id) {

	if (gd_wsl_is_wsl_user($user_id)) {
		$userattributes[] = GD_WSL_USER_ATTRIBUTE;
	}
	return $userattributes;
}

/**---------------------------------------------------------------------------------------------------------------
 * User Roles
 * ---------------------------------------------------------------------------------------------------------------*/
// Add the Profile Role when creating a new User Account with WSL
add_filter('wsl_hook_process_login_alter_wp_insert_user_data', 'gd_wsl_hook_process_login_alter_wp_insert_user_data');
function gd_wsl_hook_process_login_alter_wp_insert_user_data($userdata) {

	$userdata['role'] = GD_ROLE_PROFILE;
	return $userdata;
}

/**---------------------------------------------------------------------------------------------------------------
 * Network Links
 * ---------------------------------------------------------------------------------------------------------------*/
function gd_wsl_networklinks() {

	$current_page_url = GD_TemplateManager_Utils::get_current_url();

	$authenticate_base_url = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "action=wordpress_social_authenticate&";

	// overwrite endpoint_url if need'd
	// if( get_option( 'wsl_settings_hide_wp_login' ) == 1 ){
	// 	$authenticate_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . "/services/authenticate.php?";
	// }

	global $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

	if( empty( $social_icon_set ) ){
		$social_icon_set = "wpzoom/";
	}
	else{
		$social_icon_set .= "/";
	}
	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/32x32/' . $social_icon_set; 

	// Additions from Leo: 
	// Font Awesome icons: replace the original social media logo
	$fontawesomes = array(
		'Facebook' => 'fa-facebook',
		'Google' => 'fa-google-plus',
		'Twitter' => 'fa-twitter',
		'Yahoo' => 'fa-yahoo',
		'LinkedIn' => 'fa-linkedin'
	);
	// Short names: used for giving css hover styles to each social media link
	$shortnames = array(
		'Facebook' => 'facebook',
		'Google' => 'gplus',
		'Twitter' => 'twitter',
		'Yahoo' => 'yahoo',
		'LinkedIn' => 'linkedin'
	);


	$networklinks = array();
	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
		$provider_id     = @ $item["provider_id"];
		$provider_name   = @ $item["provider_name"]; 

		if( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ){
			
			$authenticate_url = $authenticate_base_url . "provider=" . $provider_id . "&redirect_to=" . urlencode( $current_page_url );
			$networklinks[] = array(
				'id' => $provider_id,
				'name' => $provider_name,
				'short-name' => $shortnames[$provider_id],
				'url' => $authenticate_url,
				'title' => sprintf(
					'%s %s',
					__('Log in with', 'wsl-pop'),
					$provider_name
				),
				'icon-src' => $assets_base_url . strtolower( $provider_id ) . '.png',
				'fontawesome' => 'fa-fw fa-lg '.$fontawesomes[$provider_id]
			);
		} 
	} 

	return $networklinks;
}

add_filter('gd_jquery_constants', 'gd_jquery_constants_wsl_impl');
function gd_jquery_constants_wsl_impl($jquery_constants) {

	$jquery_constants['WSL_LOGINUSER_CLOSETIME'] = apply_filters('wsl:loginuser:closetime', 1500);
	
	return $jquery_constants;
}

// Load the .js file
add_action("init", "gd_wsl_scripts_and_styles");
function gd_wsl_scripts_and_styles() {
		
	if (!is_admin()) {

		remove_action( 'wp_enqueue_scripts'   , 'wsl_add_stylesheets' );
		remove_action( 'login_enqueue_scripts', 'wsl_add_stylesheets' );
		remove_action( 'wp_enqueue_scripts'   , 'wsl_add_javascripts' );
		remove_action( 'login_enqueue_scripts', 'wsl_add_javascripts' );
	}
}
