<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EMHANDLEBARSHELPERS', PoP_TemplateIDUtils::get_template_definition('em-helpers-handlebars'));
define ('POP_RESOURCELOADER_FULLCALENDAR', PoP_TemplateIDUtils::get_template_definition('em-fullcalendar'));
define ('POP_RESOURCELOADER_CREATELOCATION', PoP_TemplateIDUtils::get_template_definition('em-create-location'));
define ('POP_RESOURCELOADER_MAPCOLLECTION', PoP_TemplateIDUtils::get_template_definition('em-map-collection'));
define ('POP_RESOURCELOADER_MAP', PoP_TemplateIDUtils::get_template_definition('em-mapa')); // Changing the name, since 'em-map' is used by the template
define ('POP_RESOURCELOADER_TYPEAHEADMAP', PoP_TemplateIDUtils::get_template_definition('em-typeahead-map'));

class EM_PoPProcessors_ResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EMHANDLEBARSHELPERS,
			POP_RESOURCELOADER_FULLCALENDAR,
			POP_RESOURCELOADER_CREATELOCATION,
			POP_RESOURCELOADER_MAPCOLLECTION,
			POP_RESOURCELOADER_MAP,
			POP_RESOURCELOADER_TYPEAHEADMAP,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_EMHANDLEBARSHELPERS => 'helpers.handlebars',
			POP_RESOURCELOADER_FULLCALENDAR => 'fullcalendar',
			POP_RESOURCELOADER_CREATELOCATION => 'create-location',
			POP_RESOURCELOADER_MAPCOLLECTION => 'map-collection',
			POP_RESOURCELOADER_MAP => 'map',
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
		switch ($resource) {

			case POP_RESOURCELOADER_FULLCALENDAR:
				
				return EM_POPPROCESSORS_DIR.'/js/'.$subpath.'libraries/3rdparties';
		}
	
		return EM_POPPROCESSORS_DIR.'/js/'.$subpath.'libraries';
	}
	
	function get_asset_path($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_FULLCALENDAR:
				
				return EM_POPPROCESSORS_DIR.'/js/libraries/3rdparties/'.$this->get_filename($resource).'.js';
		}

		return EM_POPPROCESSORS_DIR.'/js/libraries/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$afterpath = '';
		switch ($resource) {

			case POP_RESOURCELOADER_FULLCALENDAR:
				
				$afterpath = '/3rdparties';
				break;
		}

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return EM_POPPROCESSORS_URI.'/js/'.$subpath.'libraries'.$afterpath;
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_FULLCALENDAR => array(
				'popFullCalendar',
				'popFullCalendarControls',
			),
			POP_RESOURCELOADER_CREATELOCATION => array(
				'popCreateLocation',
			),
			POP_RESOURCELOADER_MAPCOLLECTION => array(
				'popMapCollection',
			),
			POP_RESOURCELOADER_MAP => array(
				'popMap',
				'popMapRuntime',
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
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_FULLCALENDAR:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR;
				
				// Add Locale file, if applicable
				if (get_em_qtransx_fullcalendar_locale_filename()) {
					$dependencies[] = POP_RESOURCELOADER_EXTERNAL_FULLCALENDARLOCALE;
				}
				break;

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
new EM_PoPProcessors_ResourceLoaderProcessor();
