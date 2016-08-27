<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Users core functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Avoid problem of duplicates when filtering Profiles
 * ---------------------------------------------------------------------------------------------------------------*/

add_action( 'pre_user_query', 'pre_user_query_avoid_duplicates');
function pre_user_query_avoid_duplicates( $user_query ) {
  $user_query->query_fields = 'SQL_CALC_FOUND_ROWS DISTINCT ' . $user_query->query_fields;
}
add_filter( 'found_users_query', 'found_users_query_avoid_duplicates');	
function found_users_query_avoid_duplicates( $sql ) {
  return 'SELECT FOUND_ROWS()';
}


/**---------------------------------------------------------------------------------------------------------------
 * Determine the threshold capability to split admin users from website users
 * ---------------------------------------------------------------------------------------------------------------*/
function user_has_access($capability, $user_id = null) {

	if (is_null($user_id)) {
		$user_id = get_current_user_id();
	}
	return user_can($user_id, $capability);
}

function user_has_admin_access($user_id = null) {

	// return current_user_can('delete_pages');
	return user_has_access('delete_pages', $user_id);
}

function gd_current_user_can_edit($post_id = null) {

	$authors = gd_get_postauthors($post_id);
	$current_user_can = current_user_can( 'edit_posts' ) && ( in_array(get_current_user_id(), $authors) );

	return $current_user_can;
}

/**---------------------------------------------------------------------------------------------------------------
 * Functions to ask if it's a specific type of user
 * ---------------------------------------------------------------------------------------------------------------*/
function has_role($role, $user_id = null) {

	if (!$user_id && is_user_logged_in()) {
	
		$user_id = get_current_user_id();
	}
	if (!$user_id) {
	
		return false;
	}
	
	$roles = get_user_roles($user_id);
	return (in_array($role, $roles));
}

/**---------------------------------------------------------------------------------------------------------------
 * Roles
 * ---------------------------------------------------------------------------------------------------------------*/

function get_current_user_role () {

    global $current_user;
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);
    
    return $user_role;
};

function get_user_roles($user_id) {

    $user = get_user_by('id', $user_id);
    $user_roles = $user->roles;
//    $user_role = array_shift($user_roles);
    
    return $user_roles;
};