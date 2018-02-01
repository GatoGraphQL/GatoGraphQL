<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServiceWorkers_ResourceLoader_MultiDomain_CDNCore_RemoveResources {

	function __construct() {

		add_filter(
			'PoP_ServiceWorkers_Frontend_ResourceLoader_ScriptsAndStylesRegistration:register_scripts',
			array($this, 'modify_resources')
		);
	}

	function modify_resources($resources) {

		// Do not add these resources to the Service Workers file:
		// POP_RESOURCELOADER_CDNCONFIG_EXTERNAL
		// (These only make sense to be added on the External page)
		$resources = array_values(array_diff(
			$resources, 
			array(
				POP_RESOURCELOADER_CDNCONFIG_EXTERNAL,
			)
		));

		return $resources;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_ResourceLoader_MultiDomain_CDNCore_RemoveResources();
