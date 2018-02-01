<?php
class PoP_CDNCore_ConfigFileGenerator extends PoP_Engine_RendererFileGeneratorBase {

	function get_dir() {

		return POP_CDNCORE_ASSETDESTINATION_DIR;
	}
	function get_url() {

		return POP_CDNCORE_ASSETDESTINATION_URL;
	}

	function get_filename() {

		return 'cdn-config.js';
	}

	function get_renderer() {

		global $pop_cdncore_filerenderer;
		return $pop_cdncore_filerenderer;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_cdncore_configfile_generator;
$pop_cdncore_configfile_generator = new PoP_CDNCore_ConfigFileGenerator();