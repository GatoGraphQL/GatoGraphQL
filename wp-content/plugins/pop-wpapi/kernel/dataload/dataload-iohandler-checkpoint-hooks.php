<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_GETUSERINFO', 'getuserinfo');

define ('GD_DATALOAD_USER', 'user');
define ('GD_DATALOAD_USER_LOGGEDIN', 'logged-in');
define ('GD_DATALOAD_USER_ID', 'id');
define ('GD_DATALOAD_USER_NAME', 'name');
define ('GD_DATALOAD_USER_AVATAR', 'avatar');
define ('GD_DATALOAD_USER_URL', 'url');
define ('GD_DATALOAD_USER_ROLES', 'roles');
define ('GD_DATALOAD_USER_ATTRIBUTES', 'userattributes');

add_filter('gd_jquery_constants', 'pop_wpapi_jquery_constants_checkpointiohandler');
function pop_wpapi_jquery_constants_checkpointiohandler($jquery_constants) {

	$jquery_constants['DATALOAD_USER'] = GD_DATALOAD_USER;	
	$jquery_constants['DATALOAD_USER_LOGGEDIN'] = GD_DATALOAD_USER_LOGGEDIN;	
	$jquery_constants['DATALOAD_USER_ID'] = GD_DATALOAD_USER_ID;	
	$jquery_constants['DATALOAD_USER_NAME'] = GD_DATALOAD_USER_NAME;	
	$jquery_constants['DATALOAD_USER_AVATAR'] = GD_DATALOAD_USER_AVATAR;	
	$jquery_constants['DATALOAD_USER_URL'] = GD_DATALOAD_USER_URL;	
	$jquery_constants['DATALOAD_USER_ROLES'] = GD_DATALOAD_USER_ROLES;	
	$jquery_constants['DATALOAD_USER_ATTRIBUTES'] = GD_DATALOAD_USER_ATTRIBUTES;	

	return $jquery_constants;
}

class PoP_WPAPI_DataLoad_CheckpointIOHandler_Hooks {

	function __construct() {

		add_filter(
			'GD_DataLoad_CheckpointIOHandler:feedback', 
			array($this, 'modify_feedback'),
			10,
			7
		);
	}

	function modify_feedback($ret, $checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {

		// Get the user info? (used for pages where user logged in is needed. Generally same as with checkpoints)
		if ($iohandler_atts[GD_DATALOAD_GETUSERINFO]) {
			
			$user_name = '';
			$avatar_user = POP_WPAPI_AVATAR_GENERICUSER;
			$user_url = '';
			$user_roles = $user_attributes = array();
			$user_logged_in = is_user_logged_in();
			if ($user_logged_in) {

				$user = wp_get_current_user();
				$user_name = $user->display_name;
				$avatar_user = $user->ID;
				$user_url = get_author_posts_url($user->ID);

				// array_values so that it discards the indexes: if will transform an array into an object
				$user_roles = array_values(
					array_intersect(
						gd_roles(), 
						$user->roles
					)
				);

				// User attributes: eg: is WSL user? Needed to hide "Change Password" link for these users
				$user_attributes = array_values(
					array_intersect(
						gd_user_attributes(), 
						gd_get_userattributes($user->ID)
					)
				);
			}
			$avatar = gd_get_avatar($avatar_user, GD_AVATAR_SIZE_100);
			$ret[GD_DATALOAD_USER] = array(
				GD_DATALOAD_USER_LOGGEDIN => $user_logged_in,
				GD_DATALOAD_USER_ID => $user->ID,
				GD_DATALOAD_USER_NAME => $user_name,
				GD_DATALOAD_USER_AVATAR => $avatar['src'],
				GD_DATALOAD_USER_URL => $user_url,
				GD_DATALOAD_USER_ROLES => $user_roles,
				GD_DATALOAD_USER_ATTRIBUTES => $user_attributes,
			);
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_WPAPI_DataLoad_CheckpointIOHandler_Hooks();
