<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_SWREGISTRAR', PoP_TemplateIDUtils::get_template_definition('sw-registrar'));

class PoP_ServiceWorkers_DynamicJSResourceLoaderProcessor extends PoP_DynamicJSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_SWREGISTRAR,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_SWREGISTRAR => 'sw-registrar',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	// function get_version($resource) {

	// // return POP_SERVICEWORKERS_VERSION;
	// 	return pop_version();
	// }
	
	function get_dir($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_SWREGISTRAR:

				global $pop_serviceworkers_manager;
				return $pop_serviceworkers_manager->get_dir();
		}
	
		return parent::get_dir($resource);
	}
	
	function get_suffix($resource) {
	
		switch ($resource) {

			case POP_RESOURCELOADER_SWREGISTRAR:
				
				// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
				return '.js';
		}
		return parent::get_suffix($resource);
	}
		
	// function extract_mapping($resource) {

	// 	// No need to extract the mapping from this file (also, it doesn't exist under that get_dir() folder)
	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_SWREGISTRAR:
				
	// 			return false;
	// 	}
	
	// 	return parent::extract_mapping($resource);
	// }
	
	function get_file_url($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_SWREGISTRAR:
				
				global $pop_serviceworkers_manager;
				return $pop_serviceworkers_manager->get_fileurl('sw-registrar.js');
		}

		return parent::get_file_url($resource);
	}
	
	// function get_path($resource) {

	// 	$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
	// 	return POP_SERVICEWORKERS_URL.'/js/'.$subpath.'libraries';
	// }

	// function can_bundle($resource) {

	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_SWREGISTRAR:
				
	// 			return false;
	// 	}
	
	// 	return parent::can_bundle($resource);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_DynamicJSResourceLoaderProcessor();
