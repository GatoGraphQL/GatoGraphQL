<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_SW', PoP_ServerUtils::get_template_definition('sw'));
define ('POP_RESOURCELOADER_SWREGISTRAR', PoP_ServerUtils::get_template_definition('sw-registrar'));

class PoP_ServiceWorkers_ResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_SW,
			POP_RESOURCELOADER_SWREGISTRAR,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_SW => 'sw',
			POP_RESOURCELOADER_SWREGISTRAR => 'sw-registrar',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {

		return POP_SERVICEWORKERS_VERSION;
	}
	
	function get_dir($resource) {
	
		return POP_SERVICEWORKERS_DIR.'/js/libraries';
	}
		
	function extract_mapping($resource) {

		// No need to extract the mapping from this file (also, it doesn't exist under that get_dir() folder)
		switch ($resource) {

			case POP_RESOURCELOADER_SWREGISTRAR:
				
				return false;
		}
	
		return parent::extract_mapping($resource);
	}
	
	function get_file_url($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_SWREGISTRAR:
				
				global $pop_serviceworkers_manager;
				return $pop_serviceworkers_manager->get_fileurl('sw-registrar.js');
		}

		return parent::get_file_url($resource);
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_SERVICEWORKERS_URI.'/js/'.$subpath.'libraries';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_SW => array(
				'popServiceWorkers',
			),
		);
		if ($object = $objects[$resource]) {

			return $object;
		}

		return parent::get_jsobjects($resource);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_ResourceLoaderProcessor();
