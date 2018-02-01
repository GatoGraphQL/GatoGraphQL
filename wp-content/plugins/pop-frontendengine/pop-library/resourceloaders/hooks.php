<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_FrontEnd_ResourceLoaderProcessor_Hooks {

	function __construct() {

		add_filter(
			'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
			array($this, 'get_manager_dependencies')
		);
	}

	function get_manager_dependencies($dependencies) {

		$dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG;
		$dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_RESOURCES;
		// $dependencies[] = POP_RESOURCELOADER_RESOURCELOADERCONFIG_INITIALRESOURCES;
		
		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_FrontEnd_ResourceLoaderProcessor_Hooks();
