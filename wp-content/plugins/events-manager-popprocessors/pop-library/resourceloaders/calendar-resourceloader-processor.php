<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_FULLCALENDAR', PoP_TemplateIDUtils::get_template_definition('em-fullcalendar'));
define ('POP_RESOURCELOADER_FULLCALENDARMEMORY', PoP_TemplateIDUtils::get_template_definition('em-fullcalendar-memory'));
define ('POP_RESOURCELOADER_FULLCALENDARADDEVENTS', PoP_TemplateIDUtils::get_template_definition('em-fullcalendar-addevents'));

class EM_PoPProcessors_CalendarResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_FULLCALENDAR,
			POP_RESOURCELOADER_FULLCALENDARMEMORY,
			POP_RESOURCELOADER_FULLCALENDARADDEVENTS,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_FULLCALENDAR => 'fullcalendar',
			POP_RESOURCELOADER_FULLCALENDARMEMORY => 'fullcalendar-memory',
			POP_RESOURCELOADER_FULLCALENDARADDEVENTS => 'fullcalendar-addevents',
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
		return EM_POPPROCESSORS_DIR.'/js/'.$subpath.'libraries/3rdparties/calendar';
	}
	
	function get_asset_path($resource) {

		return EM_POPPROCESSORS_DIR.'/js/libraries/3rdparties/calendar/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return EM_POPPROCESSORS_URI.'/js/'.$subpath.'libraries/3rdparties/calendar';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_FULLCALENDAR => array(
				'popFullCalendar',
				'popFullCalendarControls',
			),
			POP_RESOURCELOADER_FULLCALENDARMEMORY => array(
				'popFullCalendarMemory',
			),
			POP_RESOURCELOADER_FULLCALENDARADDEVENTS => array(
				'popFullCalendarAddEvents',
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
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new EM_PoPProcessors_CalendarResourceLoaderProcessor();
