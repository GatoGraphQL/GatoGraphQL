<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_CORECDNHOOKS', PoP_TemplateIDUtils::get_template_definition('core-cdn-hooks'));

class PoP_CDNCore_CoreProcessors_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_CORECDNHOOKS,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_CORECDNHOOKS => 'cdn-hooks',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POP_CDNCORE_VERSION;
	}
	
	function get_dir($resource) {
	
		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_CDNCORE_DIR.'/js/'.$subpath.'libraries/plugins/pop-coreprocessors';
	}
	
	function get_asset_path($resource) {
	
		return POP_CDNCORE_DIR.'/js/libraries/plugins/pop-coreprocessors/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_CDNCORE_URL.'/js/'.$subpath.'libraries/plugins/pop-coreprocessors';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_CORECDNHOOKS => array(
				'popCDNCoreHooks',
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
new PoP_CDNCore_CoreProcessors_ResourceLoaderProcessor();
