<?php

class PoP_ServiceWorkers_Frontend_ResourceLoader_Initialization {

	function __construct() {

		// Unhook the default function, instead hook the one here, which registers not just the current resources,
		// but all of them! This way, all resources will make it to the precache list and be cached in SW
		global $popfrontend_resourceloader_initialization;
		remove_action('wp_enqueue_scripts', array($popfrontend_resourceloader_initialization, 'register_scripts'));
		add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
	}

	function register_scripts() {

		// Register the scripts from the Resource Loader on the current request
		// Only if loading the frame, and it is configured to use code splitting
		if (!is_admin() && GD_TemplateManager_Utils::loading_frame() && PoP_Frontend_ServerUtils::use_code_splitting()) {

			global $pop_serviceworkers_frontend_resourceloader_scriptsregistration;
			$pop_serviceworkers_frontend_resourceloader_scriptsregistration->register_scripts();
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
// It is initialized inside function system_generate()
// new PoP_ServiceWorkers_Frontend_ResourceLoader_Initialization();