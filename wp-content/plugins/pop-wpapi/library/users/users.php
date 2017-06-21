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
		$vars = GD_TemplateManager_Utils::get_vars();
		$user_id = $vars['global-state']['current-user-id']/*get_current_user_id()*/;
	}
	return user_can($user_id, $capability);
}

function user_has_admin_access($user_id = null) {

	// return current_user_can('delete_pages');
	return user_has_access('delete_pages', $user_id);
}

function gd_current_user_can_edit($post_id = null) {

	$vars = GD_TemplateManager_Utils::get_vars();
	$authors = gd_get_postauthors($post_id);
	$current_user_can = current_user_can( 'edit_posts' ) && ( in_array($vars['global-state']['current-user-id']/*get_current_user_id()*/, $authors) );

	return $current_user_can;
}

