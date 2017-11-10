<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_ResourceLoaderProcessor_Hooks {

	function __construct() {

		add_filter(
			'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
			array($this, 'get_manager_dependencies')
		);
	}

	function get_manager_dependencies($dependencies) {

		// All functions in files condition-functions.js and ure-aal-functions.js 
		// are invoked through `popJSLibraryManager.execute(options.hash.method, ...`
		// Because their name is assigned in variable `options.hash.method`, and not directly, then these are not picke up
		// by the resourceLoader mapping process. As such, simply always add this JS file to be loaded
		$dependencies[] = POP_RESOURCELOADER_CONDITIONFUNCTIONS;
		$dependencies[] = POP_RESOURCELOADER_UREAALFUNCTIONS;
		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_ResourceLoaderProcessor_Hooks();
