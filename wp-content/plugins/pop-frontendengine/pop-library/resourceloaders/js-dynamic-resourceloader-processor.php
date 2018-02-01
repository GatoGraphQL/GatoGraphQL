<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG', PoP_TemplateIDUtils::get_template_definition('resourceloader-config'));
define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES', PoP_TemplateIDUtils::get_template_definition('resourceloader-config-resources'));
define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES', PoP_TemplateIDUtils::get_template_definition('resourceloader-config-initialresources'));

class PoP_FrontEnd_DynamicJSResourceLoaderProcessor extends PoP_DynamicJSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_RESOURCELOADERCONFIG,
			POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES,
			POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES,
		);
	}
	
	function get_filename($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
				
				global $pop_resourceloader_configfile_generator;
				return $pop_resourceloader_configfile_generator->get_filename();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

				global $pop_resourceloader_resources_configfile_generator;
				return $pop_resourceloader_resources_configfile_generator->get_filename();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

				global $pop_resourceloader_initialresources_configfile_generator;
				return $pop_resourceloader_initialresources_configfile_generator->get_filename();
		}

		return parent::get_filename($resource);
	}
	
	// function get_version($resource) {

	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:
				
	// 			// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
	// 			return pop_version();
	// 	}
	
	// 	return POP_FRONTENDENGINE_VERSION;
	// }
	
	// function get_suffix($resource) {
	
	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:
				
	// 			// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
	// 			return '';
	// 	}
	// 	return parent::get_suffix($resource);
	// }
	
	function get_dir($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
				
				global $pop_resourceloader_configfile_generator;
				return $pop_resourceloader_configfile_generator->get_dir();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

				global $pop_resourceloader_resources_configfile_generator;
				return $pop_resourceloader_resources_configfile_generator->get_dir();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

				global $pop_resourceloader_initialresources_configfile_generator;
				return $pop_resourceloader_initialresources_configfile_generator->get_dir();
		}
	
		return parent::get_dir($resource);
	}
		
	// function extract_mapping($resource) {

	// 	// No need to extract the mapping from this file (also, it doesn't exist under that get_dir() folder)
	// 	switch ($resource) {
			
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:
				
	// 			return false;
	// 	}
	
	// 	return parent::extract_mapping($resource);
	// }
	
	function get_file_url($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
				
				global $pop_resourceloader_configfile_generator;
				return $pop_resourceloader_configfile_generator->get_fileurl();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

				global $pop_resourceloader_resources_configfile_generator;
				return $pop_resourceloader_resources_configfile_generator->get_fileurl();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

				global $pop_resourceloader_initialresources_configfile_generator;
				return $pop_resourceloader_initialresources_configfile_generator->get_fileurl();
		}

		return parent::get_file_url($resource);
	}
	
	function is_defer($resource, $vars_hash_id) {

		switch ($resource) {
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

				return true;
		}

		return parent::is_defer($resource, $vars_hash_id);
	}
	
	// function can_bundle($resource) {

	// 	switch ($resource) {
			
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

	// 			return false;
	// 	}

	// 	return parent::can_bundle($resource);
	// }
	
	// function get_path($resource) {

	// 	$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
	// 	return POP_FRONTENDENGINE_URL.'/js/'.$subpath.'libraries';
	// }
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:

				$dependencies[] = POP_RESOURCELOADER_RESOURCELOADER;
				break;

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

				$dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG;
				break;
		}

		return $dependencies;
	}

	// function get_decorated_resources($resource) {

	// 	$decorated = parent::get_decorated_resources($resource);
	
	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
	// 		case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

	// 			$decorated[] = POP_RESOURCELOADER_POPMANAGER;
	// 			break;
	// 	}

	// 	return $decorated;
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_FrontEnd_DynamicJSResourceLoaderProcessor();
