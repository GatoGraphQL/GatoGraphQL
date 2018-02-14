<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG', PoP_TemplateIDUtils::get_template_definition('resourceloader-config'));
define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES', PoP_TemplateIDUtils::get_template_definition('resourceloader-config-resources'));
// define ('POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES', PoP_TemplateIDUtils::get_template_definition('resourceloader-config-initialresources'));

class PoP_FrontEnd_DynamicJSResourceLoaderProcessor extends PoP_DynamicJSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_RESOURCELOADERCONFIG,
			POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES,
			// POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES,
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
			
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

			// 	global $pop_resourceloader_initialresources_configfile_generator;
			// 	return $pop_resourceloader_initialresources_configfile_generator->get_filename();
		}

		return parent::get_filename($resource);
	}
	
	function get_dir($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
				
				global $pop_resourceloader_configfile_generator;
				return $pop_resourceloader_configfile_generator->get_dir();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

				global $pop_resourceloader_resources_configfile_generator;
				return $pop_resourceloader_resources_configfile_generator->get_dir();
			
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

			// 	global $pop_resourceloader_initialresources_configfile_generator;
			// 	return $pop_resourceloader_initialresources_configfile_generator->get_dir();
		}
	
		return parent::get_dir($resource);
	}
	
	function get_file_url($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:
				
				global $pop_resourceloader_configfile_generator;
				return $pop_resourceloader_configfile_generator->get_fileurl();
			
			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:

				global $pop_resourceloader_resources_configfile_generator;
				return $pop_resourceloader_resources_configfile_generator->get_fileurl();
			
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

			// 	global $pop_resourceloader_initialresources_configfile_generator;
			// 	return $pop_resourceloader_initialresources_configfile_generator->get_fileurl();
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

	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG:

				$dependencies[] = POP_RESOURCELOADER_RESOURCELOADER;
				break;

			case POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES:
			// case POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES:

				$dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG;
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_FrontEnd_DynamicJSResourceLoaderProcessor();
