<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_GADWPFUNCTIONS', PoP_TemplateIDUtils::get_template_definition('gadwp-functions'));

class GADWP_PoP_ResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_GADWPFUNCTIONS,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_GADWPFUNCTIONS => 'gadwp-functions',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return GADWP_POP_VERSION;
	}
	
	function get_dir($resource) {

		return GADWP_POP_DIR.'/js/libraries';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return GADWP_POP_URI.'/js/'.$subpath.'libraries';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_GADWPFUNCTIONS => array(
				'popGADWP',
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
new GADWP_PoP_ResourceLoaderProcessor();
