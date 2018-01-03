<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * User Role Editor plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('GD_Template_VarsUtils:add_vars', 'gd_ure_cacheprocessor_addvars');
function gd_ure_cacheprocessor_addvars($filename) {

	// Add source param for Organizations: view their profile as Community or Organization
	$vars = GD_TemplateManager_Utils::get_vars();
	if ($vars['global-state']['is-author']/*is_author()*/) {

		$author = $vars['global-state']['author']/*global $author*/;
		if (gd_ure_is_community($author)) {

			$vars = GD_TemplateManager_Utils::get_vars();		
			if ($source = $vars['source']) {
				
				$filename .= '-'.$source;
			}
		}
	}

	return $filename;
}

add_filter('GD_Template_Processor_CustomSectionBlocks:get_dataload_source:author', 'gd_ure_add_source_param', 10, 2);
function gd_ure_add_source_param($url, $user_id) {

	if (gd_ure_is_community($user_id)) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$source = $vars['source'];
		$url = GD_URE_TemplateManager_Utils::add_source($url, $source);
	}

	return $url;
}