<?php

class PoP_ServiceWorkers_Frontend_ResourceLoader_ScriptsRegistration {

	function register_scripts() {

		global $pop_resourceloaderprocessor_manager, $popfrontend_resourceloader_scriptsregistration;

		// Get all the resources
		$resources = $pop_resourceloaderprocessor_manager->get_resources();

		// Add a hook to remove unwanted resources. Eg:
		// POP_RESOURCELOADER_RESOURCELOADERCONFIG_EXTERNAL and POP_RESOURCELOADER_RESOURCELOADERCONFIG_EXTERNALRESOURCES
		// (These only make sense to be added on the External page)
		$resources = apply_filters(
			'PoP_ServiceWorkers_Frontend_ResourceLoader_ScriptsRegistration:register_scripts',
			$resources
		);

		// Register them
		$popfrontend_resourceloader_scriptsregistration->register_resources($resources);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_serviceworkers_frontend_resourceloader_scriptsregistration;
$pop_serviceworkers_frontend_resourceloader_scriptsregistration = new PoP_ServiceWorkers_Frontend_ResourceLoader_ScriptsRegistration();
