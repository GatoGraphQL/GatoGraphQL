<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * User Roles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_ROLE_PROFILE', 'profile');

// define ('GD_CAPABILITY_POST_FEATURED', 'gd_post_featured');
//define ('GD_CAPABILITY_EDIT_OTHERS_FILES', 'gd_edit_others_files');

/**---------------------------------------------------------------------------------------------------------------
 * Install
 * ---------------------------------------------------------------------------------------------------------------*/    
add_action('PoP:install', 'gd_install_roles');
function gd_install_roles() {
	
	$default = array(	'edit_posts' => true, 
						'edit_published_posts' => true,
						'read' => true , 
						'upload_files' => true , 
						'level_0' => true , 
						'level_1' => true, 
						'edit_published_pages' => true, 
						'edit_others_pages' => true,
						// GD_CAPABILITY_POST_FEATURED => false
//						,GD_CAPABILITY_EDIT_OTHERS_FILES => false
					);
						
	// Allow the library to add extra capabilities to below roles
	$extra = apply_filters('gd_users_install_extra_capabilities', array());
	
	// Merge everything together
	$capabilities = array_merge($default, $extra);

	add_role( GD_ROLE_PROFILE, 'GD Profile', $capabilities);
}

function gd_roles() {

	return apply_filters('gd_roles', array(GD_ROLE_PROFILE));
}

function gd_user_attributes() {

	return apply_filters('gd_user_attributes', array());
}

function gd_get_userattributes($user_id) {

	return apply_filters('gd_get_userattributes', array(), $user_id);
}