<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// The 'map.js' file has been divided into the following subunits, to allow as much code as possible to be loaded as defer
// Eg: executing function 'map' is non-critical, so the current 'map.js' is loaded defer, however the rest is loaded immediately
// from calling popMapInitMarker.initMarker in some .tmpl files and the consequent dependencies
define ('POP_RESOURCELOADER_MAP', PoP_TemplateIDUtils::get_template_definition('em-mapa')); // Changing the name, since 'em-map' is used by the template
define ('POP_RESOURCELOADER_MAPMEMORY', PoP_TemplateIDUtils::get_template_definition('em-map-memory'));
define ('POP_RESOURCELOADER_MAPINITMARKER', PoP_TemplateIDUtils::get_template_definition('em-map-initmarker'));
define ('POP_RESOURCELOADER_MAPRUNTIME', PoP_TemplateIDUtils::get_template_definition('em-map-runtime'));
define ('POP_RESOURCELOADER_MAPRUNTIMEMEMORY', PoP_TemplateIDUtils::get_template_definition('em-map-runtime-memory'));

class EM_PoPProcessors_MapResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_MAP,
			POP_RESOURCELOADER_MAPMEMORY,
			POP_RESOURCELOADER_MAPINITMARKER,
			POP_RESOURCELOADER_MAPRUNTIME,
			POP_RESOURCELOADER_MAPRUNTIMEMEMORY,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_MAP => 'map',
			POP_RESOURCELOADER_MAPMEMORY => 'map-memory',
			POP_RESOURCELOADER_MAPINITMARKER => 'map-initmarker',
			POP_RESOURCELOADER_MAPRUNTIME => 'map-runtime',
			POP_RESOURCELOADER_MAPRUNTIMEMEMORY => 'map-runtime-memory',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return EM_POPPROCESSORS_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return EM_POPPROCESSORS_DIR.'/js/'.$subpath.'libraries/map';
	}
	
	function get_asset_path($resource) {

		return EM_POPPROCESSORS_DIR.'/js/libraries/map/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return EM_POPPROCESSORS_URL.'/js/'.$subpath.'libraries/map';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_MAP => array(
				'popMap',
			),
			POP_RESOURCELOADER_MAPMEMORY => array(
				'popMapMemory',
			),
			POP_RESOURCELOADER_MAPINITMARKER => array(
				'popMapInitMarker',
			),
			POP_RESOURCELOADER_MAPRUNTIME => array(
				'popMapRuntime',
			),
			POP_RESOURCELOADER_MAPRUNTIMEMEMORY => array(
				'popMapRuntimeMemory',
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

			case POP_RESOURCELOADER_MAP:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_GMAPS;
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new EM_PoPProcessors_MapResourceLoaderProcessor();
