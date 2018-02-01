<?php

class PoP_ServiceWorkers_Frontend_ResourceLoader_Hooks {

	function __construct() {

		add_filter('generate_resources_on_runtime', array($this, 'skip_generate_resources_on_runtime'));
		add_filter('get_enqueuefile_type', array($this, 'get_enqueuefile_type'));
	}

	function get_enqueuefile_type($type) {

		// The AppShell, it must always use 'resource', or otherwise it will need to load extra bundle(group) files, 
		// making the initial SW pre-fetch heavy, and not allowing to easily create the AppShell for the different thememodes (embed, print)
		if ($this->is_appshell_page()) {

			return 'resource';
		}

		return $type;
	}

	function skip_generate_resources_on_runtime($skip) {

		// Indicate for what pages we can't do generate_resources_on_runtime
		// Eg: the AppShell, or otherwise we must also cache the corresponding /sitemapping/ and /settings/ .js files,
		// which we can't obtain when generating the service-worker.js file
		if ($this->is_appshell_page()) {

			return true;
		}

		return $skip;
	}

	protected function is_appshell_page() {

		// Indicate for what pages we can't do generate_resources_on_runtime
		// Eg: the AppShell, or otherwise we must also cache the corresponding /sitemapping/ and /settings/ .js files,
		// which we can't obtain when generating the service-worker.js file
		$vars = GD_TemplateManager_Utils::get_vars();
		if ($vars['global-state']['is-page']) {

			$page_id = $vars['global-state']['post']->ID;
			$skip_pages = array(
				POP_FRONTENDENGINE_PAGE_APPSHELL,
			);
			if (in_array($page_id, $skip_pages)) {

				return true;
			}
		}

		return false;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_Frontend_ResourceLoader_Hooks();