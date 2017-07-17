<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * URE Users functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_ROLE_INDIVIDUAL', 'individual');
define ('GD_URE_ROLE_ORGANIZATION', 'organization');
define ('GD_URE_ROLE_COMMUNITY', 'community');
// define ('GD_URE_ROLE_FEATUREDCOMMUNITY', 'featuredcommunity');

/**---------------------------------------------------------------------------------------------------------------
 * Install
 * ---------------------------------------------------------------------------------------------------------------*/    
add_action('PoP:system-build', 'gd_ure_install_roles');
function gd_ure_install_roles() {
	
	add_role( GD_URE_ROLE_INDIVIDUAL, 'GD Individual', array());
	add_role( GD_URE_ROLE_ORGANIZATION, 'GD Organization', array());
	add_role( GD_URE_ROLE_COMMUNITY, 'GD Community', array());
	// add_role( GD_URE_ROLE_FEATUREDCOMMUNITY, 'GD Featured Community', array());
}

/**---------------------------------------------------------------------------------------------------------------
 * Add them to the dataloader userlist
 * ---------------------------------------------------------------------------------------------------------------*/    
add_filter('gd_roles', 'gd_ure_roles_impl');
function gd_ure_roles_impl($roles) {

	$roles = array_merge(
		$roles,
		array(
			GD_URE_ROLE_ORGANIZATION, 
			GD_URE_ROLE_INDIVIDUAL, 
			GD_URE_ROLE_COMMUNITY, 
			// GD_URE_ROLE_FEATUREDCOMMUNITY
		)
	);
	return $roles;	
}


/**---------------------------------------------------------------------------------------------------------------
 * Helper Functions
 * ---------------------------------------------------------------------------------------------------------------*/    

function gd_ure_is_profile($user_id = null) {

	return has_role(GD_ROLE_PROFILE, $user_id);
}

function gd_ure_is_organization($user_id = null) {

	return has_role(GD_URE_ROLE_ORGANIZATION, $user_id);
}

function gd_ure_is_community($user_id = null) {

	// return gd_ure_is_organization($user_id);
	return has_role(GD_URE_ROLE_COMMUNITY, $user_id);
}

function gd_ure_is_individual($user_id = null) {
	
	return has_role(GD_URE_ROLE_INDIVIDUAL, $user_id);
}

function gd_ure_get_the_main_userrole($user_id = null) {
	
	if (gd_ure_is_organization($user_id)) {
		return GD_URE_ROLE_ORGANIZATION;
	}
	elseif (gd_ure_is_individual($user_id)) {
		return GD_URE_ROLE_INDIVIDUAL;
	}
	elseif (gd_ure_is_profile($user_id)) {
		return GD_ROLE_PROFILE;
	}

	return 'subscriber';
}

// Make sure we always get the most specific role
add_filter('GD_DataLoad_FieldProcessor_Users:get_value:role', 'gd_ure_getuserrole_hook', 10, 2);
function gd_ure_getuserrole_hook($role, $user_id) {

	return gd_ure_getuserrole($user_id);
}

function gd_ure_getuserrole($user_id) {

	if (gd_ure_is_organization($user_id)) {
		
		return GD_URE_ROLE_ORGANIZATION;
	}
	elseif (gd_ure_is_individual($user_id)) {
		
		return GD_URE_ROLE_INDIVIDUAL;
	}
	elseif (gd_ure_is_profile($user_id)) {
		
		return GD_ROLE_PROFILE;
	}

	$roles = get_user_roles($user_id);

	return $roles[0];
}