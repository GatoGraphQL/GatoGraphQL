<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EMHANDLEBARSHELPERS', PoP_TemplateIDUtils::get_template_definition('em-helpers-handlebars'));
define ('POP_RESOURCELOADER_CREATELOCATION', PoP_TemplateIDUtils::get_template_definition('em-create-location'));
define ('POP_RESOURCELOADER_MAPCOLLECTION', PoP_TemplateIDUtils::get_template_definition('em-map-collection'));
define ('POP_RESOURCELOADER_TYPEAHEADMAP', PoP_TemplateIDUtils::get_template_definition('em-typeahead-map'));

class EM_PoPProcessors_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EMHANDLEBARSHELPERS,
			POP_RESOURCELOADER_CREATELOCATION,
			POP_RESOURCELOADER_MAPCOLLECTION,
			POP_RESOURCELOADER_TYPEAHEADMAP,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_EMHANDLEBARSHELPERS => 'helpers.handlebars',
			POP_RESOURCELOADER_CREATELOCATION => 'create-location',
			POP_RESOURCELOADER_MAPCOLLECTION => 'map-collection',
			POP_RESOURCELOADER_TYPEAHEADMAP => 'typeahead-map',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
		
	function extract_mapping($resource) {

		// No need to extract the mapping from this file (also, it doesn't exist under that get_dir() folder)
		switch ($resource) {

			case POP_RESOURCELOADER_EMHANDLEBARSHELPERS:
				
				return false;
		}
	
		return parent::extract_mapping($resource);
	}
	
	function get_version($resource) {
	
		return EM_POPPROCESSORS_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return EM_POPPROCESSORS_DIR.'/js/'.$subpath.'libraries';
	}
	
	function get_asset_path($resource) {

		return EM_POPPROCESSORS_DIR.'/js/libraries/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return EM_POPPROCESSORS_URL.'/js/'.$subpath.'libraries';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_CREATELOCATION => array(
				'popCreateLocation',
			),
			POP_RESOURCELOADER_MAPCOLLECTION => array(
				'popMapCollection',
			),
			POP_RESOURCELOADER_TYPEAHEADMAP => array(
				'popTypeaheadMap',
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
new EM_PoPProcessors_ResourceLoaderProcessor();
