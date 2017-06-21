<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Latest Counts Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPThemeWassup_URE_LatestCounts_Hooks {

	function __construct() {

		add_filter(
			'latestcounts:author:classes', 
			array($this, 'get_classes')
		);
	}

	function get_classes($classes) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$author = $vars['global-state']['author']/*global $author*/;

		// Add all the members of the organization, if the author is an organization, and we're on the Organization+Members page
		$vars = GD_TemplateManager_Utils::get_vars();
		if (gd_ure_is_community($author) && $vars['source'] == GD_URLPARAM_URECONTENTSOURCE_COMMUNITY) {
				
			if ($members = gd_ure_get_activecontributingcontentcommunitymembers($author)) {
				foreach ($members as $member) {
					$classes[] = 'author'.$member;
				}
			}
		}
		return $classes;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_URE_LatestCounts_Hooks();
