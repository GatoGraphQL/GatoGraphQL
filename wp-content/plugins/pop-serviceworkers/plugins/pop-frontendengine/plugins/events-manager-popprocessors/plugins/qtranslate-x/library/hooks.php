<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServiceWorkers_ResourceLoader_EM_QTransX_Resources {

	function __construct() {

		add_filter(
			'PoP_ServiceWorkers_Frontend_ResourceLoader_ScriptsAndStylesRegistration:register_scripts',
			array($this, 'modify_resources')
		);
	}

	function modify_resources($resources) {

		// Whenever English is the default language, do not add the locale file, because in en/ version there is no en.js file, so the filename becomes drp.js, which does not exist, returning a 404
		// global $q_config;
		// if ($q_config['default_language'] == 'en') {
		if (!get_em_qtransx_fullcalendar_locale_filename()) {
			
			$resources = array_diff(
				$resources, 
				array(
					POP_RESOURCELOADER_EXTERNAL_FULLCALENDARLOCALE,
				)
			);
		}

		return $resources;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_ResourceLoader_EM_QTransX_Resources();
