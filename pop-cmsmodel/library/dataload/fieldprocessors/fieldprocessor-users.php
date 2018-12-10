<?php

define ('GD_DATALOAD_FIELDPROCESSOR_USERS', 'users');
 
class GD_DataLoad_FieldProcessor_Users extends GD_DataLoad_FieldProcessor {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_USERS;
	}
	
	function get_value($resultitem, $field) {
	
		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_USERS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}
					
		$cmsresolver = PoP_CMS_ObjectPropertyResolver_Factory::get_instance();
    	$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
		$user = $resultitem;
		switch ($field) {
		
			case 'role' :
				$user_roles = $cmsresolver->get_user_roles($user);

				// Allow to hook for URE: Make sure we always get the most specific role
				// Otherwise, users like Leo get role 'administrator'
			    $value = apply_filters('GD_DataLoad_FieldProcessor_Users:get_value:role', array_shift($user_roles), $this->get_id($user));
				break;
			
			case 'username' :
				$value = $cmsresolver->get_user_login($user);
				break;

			case 'user-nicename' :
			case 'nicename' :
				$value = $cmsresolver->get_user_nicename($user);
				break;

			case 'name' :				
			case 'display-name' :				
				$value = esc_attr($cmsresolver->get_user_display_name($user));
				break;

			case 'firstname' :
				$value = esc_attr($cmsresolver->get_user_firstname($user));
				break;

			case 'lastname' :
				$value = esc_attr($cmsresolver->get_user_lastname($user));
				break;	

			case 'email' :
				$value = $cmsresolver->get_user_email($user);
				break;
		
			case 'url' :
				$value = get_author_posts_url($this->get_id($user));
				break;

			case 'description' :
				
				$value = $cmsresolver->get_user_description($user);
				break;

			case 'user-url' :
				
				$value = $cmsresolver->get_user_url($user);
				break;
			
			default:

				$value = parent::get_value($resultitem, $field);
				break;																														
		}

		return $value;
	}	

	function get_id($resultitem) {

		$cmsresolver = PoP_CMS_ObjectPropertyResolver_Factory::get_instance();
		$user = $resultitem;
		return $cmsresolver->get_user_id($user);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Users();
