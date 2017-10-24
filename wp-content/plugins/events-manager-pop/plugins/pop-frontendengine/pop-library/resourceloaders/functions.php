<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

// Add the Events and Locations for the resourceLoader single path configuration
add_filter('PoP_ResourceLoader_FileReproduction_Config:configuration:category-paths', 'em_pop_resourceloader_single_paths');
function em_pop_resourceloader_single_paths($paths) {

	$paths[] = EM_POST_TYPE_EVENT_SLUG.'/';
	$paths[] = EM_POST_TYPE_LOCATION_SLUG.'/';
	return $paths;
}