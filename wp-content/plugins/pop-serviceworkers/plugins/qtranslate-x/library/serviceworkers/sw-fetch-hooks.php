<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServiceWorkers_QtransX_Job_Fetch_Hooks {

	function __construct() {

		add_filter(
			'PoP_ServiceWorkers_Job_Fetch:locales',
			array($this, 'get_locales')
		);
		add_filter(
			'PoP_ServiceWorkers_Job_Fetch:appshell_url',
			array($this, 'get_appshell_url'),
			10,
			2
		);
		add_filter(
			'PoP_ServiceWorkers_Job_Fetch:locales_byurl',
			array($this, 'get_locales_byurl')
		);
		add_filter(
			'PoP_ServiceWorkers_Job_Fetch:default_locale',
			array($this, 'get_default_locale')
		);
	}

	function get_locales($locales) {

		global $q_config;
		if ($languages = $q_config['enabled_languages']) {

			return $languages;
		}

		return $locales;
	}

	function get_appshell_url($url, $lang) {

		return qtranxf_convertURL($url, $lang);
	}

	function get_locales_byurl($locales) {

		global $q_config;
		if ($languages = $q_config['enabled_languages']) {
		
			// Ignore what has been given here, add the qTrans languages instead
			$locales = array();
			$url = trailingslashit(home_url());
			foreach ($languages as $lang) {
				
				$locales[qtranxf_convertURL($url, $lang)] = $lang;
			}
		}

		return $locales;
	}

	function get_default_locale($default) {

		if ($lang = qtranxf_getLanguage()) {

			return $lang;
		}

		return $default;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_QtransX_Job_Fetch_Hooks();
