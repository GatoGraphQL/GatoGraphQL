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
	}

	function get_multidomains($multidomains) {

		$multidomains = array_merge(
			$multidomains,
			array_keys(PoP_MultiDomain_Utils::get_multidomain_websites())
		);

		return $multidomains;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_MultiDomain_Job_Fetch_Hooks();
