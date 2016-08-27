<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Everything related to Login
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Destroy the session when the user logs out. Taken from http://silvermapleweb.com/using-the-php-session-in-wordpress/
// add_action('wp_logout', 'myEndSession');
// function myEndSession() {
// 	if(session_id()) {
// 	    session_destroy();
// 	}
// }

function gd_get_login_html($capitalize = false) {

	$html = sprintf(
		'<a href="%s">%s</a>',
		wp_login_url(),
		$capitalize ? __('Log in', 'pop-coreprocessors') : __('log in', 'pop-coreprocessors')
	);
	return apply_filters('gd_get_login_html', $html, $capitalize);
}

/**---------------------------------------------------------------------------------------------------------------
 * Override default login URL
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('login_url', 'gd_login_url', 1000, 2);
function gd_login_url($login_url, $redirect = '') {

	$login_url = get_permalink(POP_WPAPI_PAGE_LOGIN);

	// Comment Leo 14/02/2014: This is commented because this links appears above Template, so the "redirect_to"
	// will never be updated when loading pages inside the Template
	// Instead we get the redirect_to from the referrer (check library/template-manager/dataload/iohandlers/login.php)
	// if ( !empty($redirect) )
	// 	$login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
	
	
	return $login_url;
}

add_filter('lostpassword_url', 'gd_lostpassword_url', 1000, 2);
function gd_lostpassword_url($lostpassword_url, $redirect = '') {

	$lostpassword_url = get_permalink(POP_WPAPI_PAGE_LOSTPWD);

	if (!empty($redirect)) {

		$lostpassword_url = add_query_arg('redirect_to', urlencode($redirect), $lostpassword_url);
	}
	
	return $lostpassword_url;
}

add_filter('logout_url', 'gd_logout_url', 1000, 2);
function gd_logout_url($logout_url, $redirect = '') {

	if (!is_admin()) {
		
		$logout_url = get_permalink(POP_WPAPI_PAGE_LOGOUT);
	
		// Comment Leo 14/02/2014: This is commented because this links appears above Template, so the "redirect_to"
		// will never be updated when loading pages inside the Template
		// Instead we get the redirect_to from the referrer (check library/template-manager/dataload/iohandlers/login.php)
		// if ( !empty($redirect) )
		// 	$logout_url = add_query_arg('redirect_to', urlencode($redirect), $logout_url);
	}
	
	return $logout_url;
}


