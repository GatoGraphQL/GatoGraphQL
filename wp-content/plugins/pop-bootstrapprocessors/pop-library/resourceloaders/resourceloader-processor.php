<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_BOOTSTRAP', PoP_TemplateIDUtils::get_template_definition('bootstrap'));
define ('POP_RESOURCELOADER_CUSTOMBOOTSTRAP', PoP_TemplateIDUtils::get_template_definition('custombootstrap'));

class PoP_BootstrapProcessors_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_BOOTSTRAP,
			POP_RESOURCELOADER_CUSTOMBOOTSTRAP,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_BOOTSTRAP => 'bootstrap',
			POP_RESOURCELOADER_CUSTOMBOOTSTRAP => 'custombootstrap',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POP_BOOTSTRAPPROCESSORS_VERSION;
	}
	
	function get_dir($resource) {
	
		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_BOOTSTRAPPROCESSORS_DIR.'/js/'.$subpath.'libraries';
	}
	
	function get_asset_path($resource) {
	
		return POP_BOOTSTRAPPROCESSORS_DIR.'/js/libraries/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_BOOTSTRAPPROCESSORS_URI.'/js/'.$subpath.'libraries';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_BOOTSTRAP => array(
				'popBootstrap',
			),
			POP_RESOURCELOADER_CUSTOMBOOTSTRAP => array(
				'popCustomBootstrap',
			),
		);
		if ($object = $objects[$resource]) {

			return $object;
		}

		return parent::get_jsobjects($resource);
	}
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_BOOTSTRAP:
			case POP_RESOURCELOADER_CUSTOMBOOTSTRAP:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP;
				break;

			// case POP_RESOURCELOADER_CUSTOMBOOTSTRAP:

			// 	$dependencies[] = POP_RESOURCELOADER_BOOTSTRAP;
			// 	break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_BootstrapProcessors_ResourceLoaderProcessor();
