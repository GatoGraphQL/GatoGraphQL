<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServiceWorkers_ResourceLoader_MultiDomain_RemoveResources {

	function __construct() {

		add_filter(
			'PoP_ServiceWorkers_Frontend_ResourceLoader_ScriptsAndStylesRegistration:register_scripts',
			array($this, 'modify_resources')
		);
	}

	function modify_resources($resources) {

		// Do not add these resources to the Service Workers file:
		// POP_RESOURCELOADER_RESOURCELOADERCONFIG_EXTERNAL and POP_RESOURCELOADER_RESOURCELOADERCONFIG_EXTERNALRESOURCES
		// (These only make sense to be added on the External page)
		$resources = array_values(array_diff(
			$resources, 
			array(
				POP_RESOURCELOADER_RESOURCELOADERCONFIG_EXTERNAL,
				POP_RESOURCELOADER_RESOURCELOADERCONFIG_EXTERNALRESOURCES,
			)
		));

		return $resources;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_ResourceLoader_MultiDomain_RemoveResources();
