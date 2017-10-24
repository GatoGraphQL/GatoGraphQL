<?php

class PoP_ServiceWorkers_Frontend_ResourceLoader_ScriptsRegistration {

	function register_scripts() {

		global $pop_resourceloaderprocessor_manager, $popfrontend_resourceloader_scriptsregistration;

		// Get all the resources
		$resources = $pop_resourceloaderprocessor_manager->get_resources();

		// Register them
		$popfrontend_resourceloader_scriptsregistration->register_resources($resources);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_serviceworkers_frontend_resourceloader_scriptsregistration;
$pop_serviceworkers_frontend_resourceloader_scriptsregistration = new PoP_ServiceWorkers_Frontend_ResourceLoader_ScriptsRegistration();
