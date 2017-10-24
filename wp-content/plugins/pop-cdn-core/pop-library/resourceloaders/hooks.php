<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_CDNCore_ResourceLoaderProcessor_Hooks {

	function __construct() {

		add_filter(
			'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
			array($this, 'get_manager_dependencies')
		);
	}

	function get_manager_dependencies($dependencies) {

		// Add cdn-config.js, which is not referenced in the internal/external method calls by any other .js object
		$dependencies[] = POP_RESOURCELOADER_CDNCONFIG;

		// Comment Leo: Fix this!!!
		$dependencies[] = POP_RESOURCELOADER_POPUTILS;
		
		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_ResourceLoaderProcessor_Hooks();
