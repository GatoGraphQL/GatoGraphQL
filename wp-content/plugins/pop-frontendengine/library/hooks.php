<?php

class PoP_Frontend_ResourceLoader_Hooks {

	function __construct() {

		add_filter('get_enqueuefile_type', array($this, 'get_enqueuefile_type'));
	}

	function get_enqueuefile_type($type) {

		// All the build pages, do not use 'bundle' or 'bundlegroup', to make sure we're not generating these files on runtime,
		// since those updated files may not exist yet (they are generated through the build tools!) and we don't want to bundle out-of-date or non-existing versions
		if ($this->is_for_internal_use()) {

			return 'resource';
		}

		return $type;
	}

	protected function is_for_internal_use() {

		$vars = GD_TemplateManager_Utils::get_vars();
		if ($vars['global-state']['is-page']) {
			
			$hierarchy = GD_SETTINGS_HIERARCHY_PAGE;
			$page_id = $vars['global-state']['post']->ID;
		
			global $gd_template_settingsprocessor_manager;
			if (!($processor = $gd_template_settingsprocessor_manager->get_processor_by_page($page_id, $hierarchy))) {
			
				return false;
			}

			if ($internals = $processor->is_for_internal_use($hierarchy)) {
				
				return $internals[$page_id] ?? false;
			}
		}

		return false;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Frontend_ResourceLoader_Hooks();
