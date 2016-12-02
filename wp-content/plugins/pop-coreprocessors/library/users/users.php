<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Users core functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function is_profile($user_id = null) {

	return has_role(GD_ROLE_PROFILE, $user_id);
}

/**---------------------------------------------------------------------------------------------------------------
 * Determine the threshold capability to split subscriber users from admins / individual / organization users
 * ---------------------------------------------------------------------------------------------------------------*/
function user_has_profile_access($user_id = null) {

	return user_has_access('edit_posts', $user_id);
}

/**---------------------------------------------------------------------------------------------------------------
 * Author URL: Different for Profiles and Subscribers
 * Allow to override by WSL
 * ---------------------------------------------------------------------------------------------------------------*/

function gd_users_author_url($user_id) {

	return apply_filters('gd_users_author_url', get_author_posts_url($user_id), $user_id);
}

/**---------------------------------------------------------------------------------------------------------------
 * user meta
 * ---------------------------------------------------------------------------------------------------------------*/

function gd_get_user_shortdescription($user_id) {

	return GD_MetaManager::get_user_meta($user_id, GD_METAKEY_PROFILE_SHORTDESCRIPTION, true);
}

/**---------------------------------------------------------------------------------------------------------------
 * user followers and network
 * ---------------------------------------------------------------------------------------------------------------*/

function get_user_followers($user_id) {

	if ($followers = GD_MetaManager::get_user_meta($user_id, GD_METAKEY_PROFILE_FOLLOWEDBY)) {

		return $followers;
	}

	return array();
}
function get_user_networkusers($user_id) {

	// Allow URE to override with the same-community users
	return apply_filters(
		'get_user_networkusers',
		get_user_followers($user_id),
		$user_id
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * User IDs
 * ---------------------------------------------------------------------------------------------------------------*/

function gd_fixedscroll_user_ids($template_id) {
	
	return apply_filters('gd_fixedscroll_user_ids', array(), $template_id);	
}