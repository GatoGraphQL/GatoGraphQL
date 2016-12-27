<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServiceWorkers_QtransX_Job_Fetch_Hooks {

	function __construct() {

		// add_filter(
		// 	'PoP_ServiceWorkers_Job_Fetch:offline_pages',
		// 	array($this, 'get_offline_pages')
		// );
		add_filter(
			'PoP_ServiceWorkers_Job_Fetch:appshell_pages',
			array($this, 'get_appshell_pages')
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

	// function get_offline_pages($pages) {

	// 	// Add the output=json because the offline will always be called from inside the website, so by then it must all be json
 //        if ($localepages = $this->get_localepages_from_page(POP_SERVICEWORKERS_PAGE_OFFLINE, true)) {
	// 		return $localepages;
	// 	}

	// 	return $pages;
	// }

	function get_appshell_pages($pages) {

		if ($localepages = $this->get_localepages_from_page(POP_SERVICEWORKERS_PAGE_APPSHELL)) {
			return $localepages;
		}

		return $pages;
	}

	protected function get_localepages_from_page($page, $addjson = false) {

		$pages = array();
		if ($page) {

			global $q_config;
			if ($languages = $q_config['enabled_languages']) {
			
				// Ignore what has been given here, add the qTrans languages instead
				$url = get_permalink($page);
				if ($addjson) {
					
					// Add the output=json because the offline will always be called from inside the website, so by then it must all be json
		            $url = add_query_arg(GD_URLPARAM_OUTPUT, GD_URLPARAM_OUTPUT_JSON, $url);
				}
				foreach ($languages as $lang) {
					
					$pages[$lang] = qtranxf_convertURL($url, $lang);
				}
			}
		}

		return $pages;
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
