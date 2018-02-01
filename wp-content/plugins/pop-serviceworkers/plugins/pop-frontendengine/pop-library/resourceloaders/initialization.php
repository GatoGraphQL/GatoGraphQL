<?php

class PoP_ServiceWorkers_Frontend_ResourceLoader_Initialization {

	function __construct() {

		// Unhook the default function, instead hook the one here, which registers not just the current resources,
		// but all of them! This way, all resources will make it to the precache list and be cached in SW
		global $popfrontend_resourceloader_initialization;
		remove_action('wp_enqueue_scripts', array($popfrontend_resourceloader_initialization, 'register_scripts'));
		add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
		remove_action('wp_print_styles', array($popfrontend_resourceloader_initialization, 'register_styles'));
		add_action('wp_print_styles', array($this, 'register_styles'));

		// Priority 70: after the `enqueue_scripts` function in wp-content/plugins/pop-engine/kernel/pop-engine.php
		add_action('wp_enqueue_scripts', array($this, 'deregister_scripts'), 70);

		// When generating the Service Workers, we need to register all of the CSS files to output them in the precache list
		add_filter('get_templateresources_include_type', array($this, 'get_templateresources_include_type'));

		// Always use the SW creation in 'resource' mode, to avoid $bundle(group)s being enqueued instead of $resources
		add_filter('get_enqueuefile_type', array($this, 'get_enqueuefile_type'));
	}

	function get_templateresources_include_type($type) {

		// When generating the Service Workers, we need to register all of the CSS files to output them in the precache list.
		// By using 'header', the styles will be registered through WP standard function, from where we fetch the resources
		return 'header';
	}

	function get_enqueuefile_type($type) {

		// Always use the SW creation in 'resource' mode, to avoid $bundle(group)s being enqueued instead of $resources
		return 'resource';
	}

	function register_scripts() {

		// Register the scripts from the Resource Loader on the current request
		// Only if loading the frame, and it is configured to use code splitting
		if (!is_admin() && GD_TemplateManager_Utils::loading_frame() && PoP_Frontend_ServerUtils::use_code_splitting()) {

			global $pop_serviceworkers_frontend_resourceloader_scriptsandstyles_registration;
			$pop_serviceworkers_frontend_resourceloader_scriptsandstyles_registration->register_scripts();
		}
	}

	function register_styles() {

		// Register the scripts from the Resource Loader on the current request
		// Only if loading the frame, and it is configured to use code splitting
		if (!is_admin() && GD_TemplateManager_Utils::loading_frame() && PoP_Frontend_ServerUtils::use_code_splitting()) {

			global $pop_serviceworkers_frontend_resourceloader_scriptsandstyles_registration;
			$pop_serviceworkers_frontend_resourceloader_scriptsandstyles_registration->register_styles();
		}
	}

	function deregister_scripts() {

		// Register the scripts from the Resource Loader on the current request
		// Only if loading the frame, and it is configured to use code splitting
		if (!is_admin() && GD_TemplateManager_Utils::loading_frame() && PoP_Frontend_ServerUtils::use_code_splitting()) {

			// Dequeue unwanted scripts. Eg: the /sitemapping/ and /settings/ runtime generated files, which correspond to the /generate/ page and will not be needed by anyone else
			if (PoP_Frontend_ServerUtils::generate_resources_on_runtime()) {

				$properties = array(
					'sitemapping',
					'settings',
				);
				foreach ($properties as $property) {
					wp_dequeue_script('pop-'.$property);
				}
			}
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
// It is initialized inside function system_generate()
// new PoP_ServiceWorkers_Frontend_ResourceLoader_Initialization();