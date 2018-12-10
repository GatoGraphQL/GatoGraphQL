<?php

class PoP_CMSModel_Engine_Utils {
	
	public static function reset() {

		// From the new URI set in $_SERVER['REQUEST_URI'], re-generate $vars
		$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
		$query = $cmsapi->get_query_from_request_uri();
		PoP_ModuleManager_Vars::set_query($query);
	}

	public static function calculate_and_set_vars_state_reset($vars_in_array, $query) {

		// Set additional properties based on the hierarchy: the global $post, $author, or $queried_object
		$vars = &$vars_in_array[0];
		$hierarchy = $vars['hierarchy'];
		$has_queried_object_hierarchies = array(
			GD_SETTINGS_HIERARCHY_TAG,
			GD_SETTINGS_HIERARCHY_PAGE,
			GD_SETTINGS_HIERARCHY_SINGLE,
			GD_SETTINGS_HIERARCHY_AUTHOR,
			GD_SETTINGS_HIERARCHY_CATEGORY,
		);
		if (in_array($hierarchy, $has_queried_object_hierarchies)) {

			$vars['global-state']['queried-object'] = $query->get_queried_object();
			$vars['global-state']['queried-object-id'] = $query->get_queried_object_id();
		}
	}

	public static function calculate_and_set_vars_state($vars_in_array, $query) {

		// Set additional properties based on the hierarchy: the global $post, $author, or $queried_object
		$vars = &$vars_in_array[0];
		$hierarchy = $vars['hierarchy'];

		// Attributes needed to match the PageModuleProcessor vars conditions
		if ($hierarchy == GD_SETTINGS_HIERARCHY_SINGLE) {

			$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
			$post_id = $vars['global-state']['queried-object-id'];
			$vars['global-state']['queried-object-post-type'] = $cmsapi->get_post_type($post_id);
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
add_action('PoP_ModuleManager_Utils:reset', array(PoP_CMSModel_Engine_Utils::class, 'reset'), 0); // Priority 0: execute immediately, to set the $query before anyone needs to use it
add_action('PoP_ModuleManager_Utils:calculate_and_set_vars_state', array(PoP_CMSModel_Engine_Utils::class, 'calculate_and_set_vars_state'), 0, 2);
add_action('PoP_ModuleManager_Utils:calculate_and_set_vars_state:reset', array(PoP_CMSModel_Engine_Utils::class, 'calculate_and_set_vars_state_reset'), 0, 2);
