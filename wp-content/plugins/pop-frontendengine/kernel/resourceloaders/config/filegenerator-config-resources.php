<?php
// class PoP_ResourceLoader_ConfigResourcesFileGenerator extends PoP_ResourceLoader_ResourcesFileGeneratorBase {
class PoP_ResourceLoader_ConfigResourcesFileGenerator extends PoP_ResourceLoader_ConfigFileGeneratorBase {

	function get_filename() {

		return 'resources.js';
	}

	function get_renderer() {

		global $pop_resourceloader_resources_configfile_renderer;
		return $pop_resourceloader_resources_configfile_renderer;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_resources_configfile_generator;
$pop_resourceloader_resources_configfile_generator = new PoP_ResourceLoader_ConfigResourcesFileGenerator();