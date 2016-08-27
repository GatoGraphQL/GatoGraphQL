<?php
/**
 * bbPress 2.2 uses dynamic roles, and hooks very early into WP_Roles and the option_wp_user_roles section.
 * This is done to override the default role and apply relevant caps, but then deletes our custom caps as a result.
 * This functions queries the DB directly and re-adds these caps to the bbPress roles depending on settings
 * @param unknown $roles
 */
function em_bbp_get_caps_for_role( $caps, $role ){
	global $em_capabilities_array, $wpdb;
	if( bbp_is_deactivation() ) return $caps;
	//get the non-dynamic role from the wp_options table
	$roles = maybe_unserialize($wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name='wp_user_roles'"));
	//loop through the original role if it exists and add our em caps to the bp role
	if( !empty($roles[$role]) ){
		foreach($roles[$role]['capabilities'] as $cap_name => $has_cap ){
			if( array_key_exists($cap_name, $em_capabilities_array) ){
				$caps[$cap_name] = $has_cap;
			}
		}
	}
	return $caps;
}
add_filter('bbp_get_caps_for_role', 'em_bbp_get_caps_for_role', 10, 2);