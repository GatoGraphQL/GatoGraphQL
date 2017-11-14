<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Users core functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Functions to ask if it's a specific type of user
 * ---------------------------------------------------------------------------------------------------------------*/
function has_role($role, $user_id = null) {

	// Comment Leo 20/06/2017: function has_role is called by gd_ure_is_organization, called by PoP_URE_Engine_Utils::get_vars
	// So here it can produce an indefinite loop, whenever $user_is is null
	// For that, we ask that first, and only in that case we obtain $vars
	if (!$user_id) {
	
		$vars = GD_TemplateManager_Utils::get_vars();
		if ($vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {
	
			$user_id = $vars['global-state']['current-user-id']/*get_current_user_id()*/;
		}
		
		if (!$user_id) {

			return false;
		}
	}
	
	$roles = get_user_roles($user_id);
	return (in_array($role, $roles));
}

/**---------------------------------------------------------------------------------------------------------------
 * Roles
 * ---------------------------------------------------------------------------------------------------------------*/

function get_user_roles($user_id) {

    $user = get_user_by('id', $user_id);
    $user_roles = $user->roles;
//    $user_role = array_shift($user_roles);
    
    return $user_roles;
};

function get_the_user_role($user_id) {

	$roles = get_user_roles($user_id);

	// Allow URE to override this function
	return apply_filters('get_the_user_role', $roles[0], $user_id);
}