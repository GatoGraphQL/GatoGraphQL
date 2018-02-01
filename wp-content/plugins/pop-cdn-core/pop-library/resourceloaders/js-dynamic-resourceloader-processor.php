<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_CDNCONFIG', PoP_TemplateIDUtils::get_template_definition('cdn-config'));

class PoP_CDNCore_DynamicJSResourceLoaderProcessor extends PoP_DynamicJSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_CDNCONFIG,
		);
	}
	
	function get_filename($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_CDNCONFIG:

				global $pop_cdncore_configfile_generator;
				return $pop_cdncore_configfile_generator->get_filename();
		}

		return parent::get_filename($resource);
	}
	
	// function get_suffix($resource) {
	
	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_CDNCONFIG:
				
	// 			// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
	// 			return '';
	// 	}
	// 	return parent::get_suffix($resource);
	// }
	
	// function get_version($resource) {

	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_CDNCONFIG:
				
	// 			// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
	// 			return pop_version();
	// 	}
	
	// 	return POP_CDNCORE_VERSION;
	// }
	
	function get_dir($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_CDNCONFIG:

				global $pop_cdncore_configfile_generator;
				return $pop_cdncore_configfile_generator->get_dir();
		}
	
		return parent::get_dir($resource);
	}
		
	// function extract_mapping($resource) {

	// 	// No need to extract the mapping from this file (also, it doesn't exist under that get_dir() folder)
	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_CDNCONFIG:
				
	// 			return false;
	// 	}
	
	// 	return parent::extract_mapping($resource);
	// }
		
	// function can_bundle($resource) {

	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_CDNCONFIG:
				
	// 			return false;
	// 	}
	
	// 	return parent::can_bundle($resource);
	// }
	
	function get_file_url($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_CDNCONFIG:
				
				global $pop_cdncore_configfile_generator;
				return $pop_cdncore_configfile_generator->get_fileurl();
		}

		return parent::get_file_url($resource);
	}
	
	// function get_path($resource) {

	// 	$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
	// 	return POP_CDNCORE_URL.'/js/'.$subpath.'libraries';
	// }

	// function get_jsobjects($resource) {

	// 	$objects = array(
	// 		POP_RESOURCELOADER_CDNCONFIG => array(
	// 			'popCDNConfig',
	// 		),
	// 	);
	// 	if ($object = $objects[$resource]) {

	// 		return $object;
	// 	}

	// 	return parent::get_jsobjects($resource);
	// }
	
	function get_decorated_resources($resource) {

		$decorated = parent::get_decorated_resources($resource);
	
		switch ($resource) {
		
			case POP_RESOURCELOADER_CDNCONFIG:

				$decorated[] = POP_RESOURCELOADER_CDN;
				break;
		}

		return $decorated;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_DynamicJSResourceLoaderProcessor();
