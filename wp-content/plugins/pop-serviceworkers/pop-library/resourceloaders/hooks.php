<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServiceWorkers_ResourceLoaderProcessor_Hooks {

	function __construct() {

		add_filter(
			'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
			array($this, 'get_manager_dependencies')
		);
	}

	function get_manager_dependencies($dependencies) {

		// Add sw-registrar.js
		$dependencies[] = POP_RESOURCELOADER_SWREGISTRAR;
		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_ResourceLoaderProcessor_Hooks();
