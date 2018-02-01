<?php
class PoP_ResourceLoader_ConfigInitialResourcesFileGenerator extends PoP_ResourceLoader_ConfigAddResourcesFileGeneratorBase {

	function get_filename() {

		return 'initialresources.js';
	}

	function get_renderer() {

		global $pop_resourceloader_initialresources_configfile_renderer;
		return $pop_resourceloader_initialresources_configfile_renderer;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_initialresources_configfile_generator;
$pop_resourceloader_initialresources_configfile_generator = new PoP_ResourceLoader_ConfigInitialResourcesFileGenerator();