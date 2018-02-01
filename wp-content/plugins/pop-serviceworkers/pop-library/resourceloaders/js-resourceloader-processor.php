<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_SW', PoP_TemplateIDUtils::get_template_definition('sw'));
define ('POP_RESOURCELOADER_SWINITIAL', PoP_TemplateIDUtils::get_template_definition('sw-initial'));

class PoP_ServiceWorkers_JSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_SW,
			POP_RESOURCELOADER_SWINITIAL,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_SW => 'sw',
			POP_RESOURCELOADER_SWINITIAL => 'sw-initial',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {

		return POP_SERVICEWORKERS_VERSION;
	}
	
	function get_dir($resource) {
	
		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_SERVICEWORKERS_DIR.'/js/'.$subpath.'libraries';
	}
	
	function get_asset_path($resource) {

		return POP_SERVICEWORKERS_DIR.'/js/libraries/'.$this->get_filename($resource).'.js';
	}
	
		
	function extract_mapping($resource) {

		// No need to extract the mapping from this file (also, it doesn't exist under that get_dir() folder)
		switch ($resource) {

			case POP_RESOURCELOADER_SWINITIAL:
				
				return false;
		}
	
		return parent::extract_mapping($resource);
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_SERVICEWORKERS_URL.'/js/'.$subpath.'libraries';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_SW => array(
				'popServiceWorkers',
			),
		);
		if ($object = $objects[$resource]) {

			return $object;
		}

		return parent::get_jsobjects($resource);
	}
	
	function inline($resource) {

		switch ($resource) {

			// File sw-initial.js is needed because executing `fetch(cacheBustRequest, fetchOpts)` in service-worker.js happens so fast,
			// that quite likely sw.js is still not loaded, so it will not catch that first message triggered when the initial page has been updated
			case POP_RESOURCELOADER_SWINITIAL:
				
				return true;
		}
	
		return parent::inline($resource);
	}
	
	function in_footer($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_SWINITIAL:
				
				return false;
		}
	
		return parent::in_footer($resource);
	}
	
	function can_bundle($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_SWINITIAL:
				
				return false;
		}
	
		return parent::can_bundle($resource);
	}
	
	function get_decorated_resources($resource) {

		$decorated = parent::get_decorated_resources($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_SWINITIAL:

				$decorated[] = POP_RESOURCELOADER_SW;
				break;
		}

		return $decorated;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_JSResourceLoaderProcessor();
