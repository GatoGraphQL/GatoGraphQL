<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PoP User Avatar Plugin functions for the Wordpress Social Login
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Do not use the original WSL get_avatar function
remove_filter( 'get_avatar', 'wsl_get_wp_user_custom_avatar', 10, 5 );

// Instead, check if PoP_UserAvatar is displaying the default avatar, only then use the WSL avatar
// (TemplatManager user-set avatar has priority)
add_filter('gd_avatar_default', 'gd_wsl_avatar', 100, 5);
function gd_wsl_avatar($html, $user, $size, $default, $alt) {
	
	// If passed an object, assume $user->user_id
	if ( is_object( $user ) )
		$user_id = $user->user_id;

	// If passed a number, assume it was a $user_id
	else if ( is_numeric( $user ) )
		$user_id = $user;

	// If passed a string and that string returns a user, get the $id
	else if ( is_string( $user ) && ( $user_by_email = get_user_by_email( $user ) ) )
		$user_id = $user_by_email->ID;

	// User found?
	if ($user_id) {

		if ($wsl_avatar = wsl_get_user_custom_avatar($user_id)) {
			
			$wsl_html = '<img alt="'. $alt .'" src="' . $wsl_avatar . '" class="avatar avatar-wordpress-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';

			// HOOKABLE: 
			return apply_filters( 'gd_wsl_hook_alter_wp_user_custom_avatar', $wsl_html, $user_id, $wsl_avatar, $html, $user, $size, $default, $alt );
		}
	}

	return $html;
}

