<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServiceWorkers_MultiDomain_Job_Fetch_Hooks {

	function __construct() {

		add_filter(
			'PoP_ServiceWorkers_Job_Fetch:multidomains',
			array($this, 'get_multidomains')
		);

		add_filter(
			'PoP_ServiceWorkers_Job_Fetch:multidomain-locales',
			array($this, 'get_multidomain_locales')
		);
	}

	function get_multidomains($multidomains) {

		$multidomains = array_merge(
			$multidomains,
			array_keys(PoP_MultiDomain_Utils::get_multidomain_websites())
		);

		return $multidomains;
	}

	function get_multidomain_locales($multidomain_locales) {

		$multidomain_websites = PoP_MultiDomain_Utils::get_multidomain_websites();
		foreach ($multidomain_websites as $domain => $website) {

			if ($locale = $website['locale']) {

				$multidomain_locales[] = $locale;
			}
		}

		return $multidomain_locales;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_MultiDomain_Job_Fetch_Hooks();
