<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

add_filter('pop_sw_main_pagesection_ids', 'poptheme_wassup_sw_main_pagesection_ids');
function poptheme_wassup_sw_main_pagesection_ids($pagesection_ids) {

	// PageSections where the message "Please refresh your content" for stale JSON requests will be shown
	$pagesection_ids[] = GD_TEMPLATEID_PAGESECTIONID_MAIN;
	$pagesection_ids[] = GD_TEMPLATEID_PAGESECTIONID_ADDONS;
	return $pagesection_ids;
}