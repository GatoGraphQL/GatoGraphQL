<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_NOTIFICATIONS', PoP_TemplateIDUtils::get_template_definition('notifications'));

class PoP_AAL_Processors_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_NOTIFICATIONS,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_NOTIFICATIONS => 'notifications',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return AAL_POPPROCESSORS_VERSION;
	}
	
	function get_dir($resource) {
	
		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return AAL_POPPROCESSORS_DIR.'/js/'.$subpath.'libraries';
	}
	
	function get_asset_path($resource) {

		return AAL_POPPROCESSORS_DIR.'/js/libraries/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return AAL_POPPROCESSORS_URI.'/js/'.$subpath.'libraries';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_NOTIFICATIONS => array(
				'popNotifications',
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

			case POP_RESOURCELOADER_NOTIFICATIONS:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_MOMENT;
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_AAL_Processors_ResourceLoaderProcessor();
