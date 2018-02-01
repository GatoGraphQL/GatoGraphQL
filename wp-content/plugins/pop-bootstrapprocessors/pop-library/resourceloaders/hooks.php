<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_BootstrapProcessors_ResourceLoaderProcessor_Hooks {

	function __construct() {

		add_filter(
			'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
			array($this, 'get_manager_dependencies')
		);
	}

	function get_manager_dependencies($dependencies) {

		// Always load Bootstrap
		$dependencies[] = POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP;
		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_BootstrapProcessors_ResourceLoaderProcessor_Hooks();
