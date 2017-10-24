<?php
class PoP_ResourceLoader_ConfigFileGenerator extends PoP_ResourceLoader_ConfigFileGeneratorBase {

	function get_filename() {

		return 'config.js';
	}

	function get_renderer() {

		global $pop_resourceloader_configfile_renderer;
		return $pop_resourceloader_configfile_renderer;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_configfile_generator;
$pop_resourceloader_configfile_generator = new PoP_ResourceLoader_ConfigFileGenerator();