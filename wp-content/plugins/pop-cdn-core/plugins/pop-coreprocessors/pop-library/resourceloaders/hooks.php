<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_CDNCore_CoreProcessors_ResourceLoaderProcessor_Hooks {

	function __construct() {

		add_filter(
			'PoP_CDNCore_ResourceLoaderProcessor:dependencies',
			array($this, 'get_cdn_dependencies')
		);
	}

	function get_cdn_dependencies($dependencies) {

		$dependencies[] = POP_RESOURCELOADER_CORECDNHOOKS;
		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_CoreProcessors_ResourceLoaderProcessor_Hooks();
