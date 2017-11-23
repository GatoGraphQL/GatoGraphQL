<?php

class PoPFrontend_ResourceLoader_Initialization {

	function __construct() {

		// Wait until the system is initialized, so we can access $vars
		// These 2 functions (register and localize) are separated into 2 calls, so that they can independently 
		// be unhooked (eg: by Service Workers)
		add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 50);
		add_action('wp_enqueue_scripts', array($this, 'localize_scripts'), 70);
		add_action('wp_print_styles', array($this, 'register_styles'), 50);
	}

	function register_scripts() {

		// Register the scripts from the Resource Loader on the current request
		// Only if loading the frame, and it is configured to use code splitting
		if (!is_admin() && GD_TemplateManager_Utils::loading_frame() && PoP_Frontend_ServerUtils::use_code_splitting()) {

			global $popfrontend_resourceloader_scriptsandstyles_registration;
			$popfrontend_resourceloader_scriptsandstyles_registration->register_scripts();
		}
	}

	function register_styles() {

		// Register the scripts from the Resource Loader on the current request
		// Only if loading the frame, and it is configured to use code splitting
		if (!is_admin() && GD_TemplateManager_Utils::loading_frame() && PoP_Frontend_ServerUtils::use_code_splitting()) {

			global $popfrontend_resourceloader_scriptsandstyles_registration;
			$popfrontend_resourceloader_scriptsandstyles_registration->register_styles();
		}
	}

	function localize_scripts() {

		// Only if loading the frame, and it is configured to use code splitting
		if (!is_admin() && GD_TemplateManager_Utils::loading_frame() && PoP_Frontend_ServerUtils::use_code_splitting()) {

			// Also localize the scripts
			global $pop_jsresourceloaderprocessor_manager;
			$pop_jsresourceloaderprocessor_manager->localize_scripts();
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $popfrontend_resourceloader_initialization;
$popfrontend_resourceloader_initialization = new PoPFrontend_ResourceLoader_Initialization();