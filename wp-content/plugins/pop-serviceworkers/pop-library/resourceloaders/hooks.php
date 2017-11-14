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

		// Comment Leo 14/11/2017: enqueue scripts only if we enable SW by configuration
		if (PoP_Frontend_ServerUtils::use_serviceworkers()) {

			// Comment Leo 14/11/2017: we must execute /sw.js before executing /sw-registrar.js
			// Otherwise, when first loading the page, if it has changed, it will not show the message "Please click here to reload the page",
			// since the code to execute that is executed after the SW 'refresh' function...
			$dependencies[] = POP_RESOURCELOADER_SW;

			// Add sw-registrar.js
			$dependencies[] = POP_RESOURCELOADER_SWREGISTRAR;
		}
		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_ResourceLoaderProcessor_Hooks();
