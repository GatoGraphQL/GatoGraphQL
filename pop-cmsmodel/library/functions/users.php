<?php

/**---------------------------------------------------------------------------------------------------------------
 * Functions to ask if it's a specific type of user
 * ---------------------------------------------------------------------------------------------------------------*/
function has_role($role, $user_or_user_id) {

	if (is_object($user_or_user_id)) {
		$user = $user_or_user_id;
		$user_id = $user->ID;
	}
	else {
		$user_id = $user_or_user_id;
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
    
    return $user_roles;
};

function get_the_user_role($user_id) {

	$roles = get_user_roles($user_id);

	// Allow URE to override this function
	return apply_filters('get_the_user_role', $roles[0], $user_id);
}