<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * User Role Editor Plugin functions for the Wordpress Social Login
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * User Roles
 * ---------------------------------------------------------------------------------------------------------------*/
// Add the Profile and Individual Roles when creating a new User Account with WSL
add_action('wsl_hook_process_login_after_wp_insert_user', 'gd_wsl_ure_hook_process_login_after_wp_insert_user', 10, 1);
function gd_wsl_ure_hook_process_login_after_wp_insert_user($user_id) {

	// GD_ROLE_PROFILE alredy added. Now add the Individual role
	$user = get_user_by('id', $user_id);
	$user->add_role(GD_URE_ROLE_INDIVIDUAL);
}
