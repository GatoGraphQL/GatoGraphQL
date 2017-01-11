<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * WP Super Cache Plugin functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Hook together the is_search_engine function with WP Super Cache's is_rejected_user_agent function
add_filter('GD_TemplateManager_Utils:is_search_engine', 'gd_wp_cache_is_rejected_user_agent');
function gd_wp_cache_is_rejected_user_agent($is_search_engine) {

	// If the user agent is rejected, then it is a crawler
	return wp_cache_user_agent_is_rejected();
}


/**---------------------------------------------------------------------------------------------------------------
 * Ignore files to cache: all the ones with checkpoint needed
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_wp_cache_set_rejected_uri', 'gd_wp_cache_set_rejected_uri_checkpoints');
function gd_wp_cache_set_rejected_uri_checkpoints($rejected_uris) {

	// Only if the type if GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER. The Static type can be cached since it contains no data
	// Type GD_DATALOAD_NOCHECKPOINTVALIDATION_TYPE_DATAFROMSERVER was added as to reject NEVERCACHE pages, even though they need no checkpoint validation (Eg: Notifications)
	$reject_types = array(
		GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
		GD_DATALOAD_NOCHECKPOINTVALIDATION_TYPE_DATAFROMSERVER,
	);
	
	// All the files with a checkpoint must not be cached
	global $gd_template_settingsprocessor_manager;
	foreach ($gd_template_settingsprocessor_manager->get_processors() as $settingsprocessor) {
		foreach ($settingsprocessor->get_checkpoints(GD_SETTINGS_HIERARCHY_PAGE) as $page => $settings) {

			// The ID might've not been defined for that page (eg: Projects in TPP Debate), so skip it
			if (!$page) continue;

			if (in_array($settings['type'], $reject_types)) {
			
				$rejected_uris[] = '/'.GD_TemplateManager_Utils::get_page_path($page).'/';
			}
		}
	}
	return $rejected_uris;
}