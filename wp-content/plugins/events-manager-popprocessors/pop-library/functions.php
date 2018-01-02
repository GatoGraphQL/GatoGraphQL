<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD Locations Map
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_jquery_constants', 'gd_em_jquery_constants_locations');
function gd_em_jquery_constants_locations($jquery_constants) {

	$jquery_constants['LOCATIONSID_FIELDNAME'] = GD_TEMPLATE_FORMCOMPONENT_LOCATIONID;
	return $jquery_constants;
}

// Event and Past Event have different configurations, so we must differentiate among them
add_filter('GD_Template_VarsUtils:add_vars', 'pop_em_cacheprocessor_addvars');
function pop_em_cacheprocessor_addvars($filename) {

	// Add source param for Organizations: view their profile as Community or Organization
	$vars = GD_TemplateManager_Utils::get_vars();
	if ($vars['global-state']['is-single']) {

		$post = $vars['global-state']['post']/*global $author*/;
		if ($post->post_type == EM_POST_TYPE_EVENT && !gd_em_single_event_is_future($post->ID)) {

			$filename .= '-past';
		}
	}

	return $filename;
}

