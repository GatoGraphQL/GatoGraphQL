<?php

class PoPEngine_Module_SettingsManager {

	function __construct() {

		PoPEngine_Module_SettingsManager_Factory::set_instance($this);
	}

	function get_checkpoint_configuration($page_id = null) {

		$vars = PoP_ModuleManager_Vars::get_vars();
		if (!$page_id) {

			$page_id = PoP_ModuleManager_Utils::get_hierarchy_page_id();
		}

		$pop_module_settingsprocessor_manager = PoPEngine_Module_SettingsProcessorManager_Factory::get_instance();
		$processor = $pop_module_settingsprocessor_manager->get_processor($page_id/*, $hierarchy*/);
		$checkpoints = $processor->get_checkpoint_configuration();
		if ($checkpoints[$page_id]) {

			return $checkpoints[$page_id];
		}

		return array(
			'checkpoints' => array(),
			'type' => false
		);
	}

	function is_functional($page_id = null) {

		$vars = PoP_ModuleManager_Vars::get_vars();
		if (!$page_id) {

			$page_id = PoP_ModuleManager_Utils::get_hierarchy_page_id();
		}

		$pop_module_settingsprocessor_manager = PoPEngine_Module_SettingsProcessorManager_Factory::get_instance();
		$processor = $pop_module_settingsprocessor_manager->get_processor($page_id);

		// If we get an array, then it defines the value on a page by page basis
		// Otherwise, it's true/false, just return it
		$functional = $processor->is_functional();
		if (is_array($functional)) {

			return $functional[$page_id];
		}

		return $functional;
	}

	function is_for_internal_use($page_id = null) {

		$vars = PoP_ModuleManager_Vars::get_vars();
		if (!$page_id) {

			$page_id = PoP_ModuleManager_Utils::get_hierarchy_page_id();
		}

		$pop_module_settingsprocessor_manager = PoPEngine_Module_SettingsProcessorManager_Factory::get_instance();
		$processor = $pop_module_settingsprocessor_manager->get_processor($page_id);

		// If we get an array, then it defines the value on a page by page basis
		// Otherwise, it's true/false, just return it
		$internal = $processor->is_for_internal_use();
		if (is_array($internal)) {

			return $internal[$page_id];
		}

		return $internal;
	}

	function needs_target_id($page_id = null) {

		$vars = PoP_ModuleManager_Vars::get_vars();
		if (!$page_id) {

			$page_id = PoP_ModuleManager_Utils::get_hierarchy_page_id();
		}
		
		$pop_module_settingsprocessor_manager = PoPEngine_Module_SettingsProcessorManager_Factory::get_instance();
		$processor = $pop_module_settingsprocessor_manager->get_processor($page_id);

		// If we get an array, then it defines the value on a page by page basis
		// Otherwise, it's true/false, just return it
		$targetids = $processor->needs_target_id();
		if (is_array($targetids)) {

			return $targetids[$page_id];
		}

		return $targetids;
	}

	function get_redirect_url($page_id = null) {

		$vars = PoP_ModuleManager_Vars::get_vars();
		if (!$page_id) {

			$page_id = PoP_ModuleManager_Utils::get_hierarchy_page_id();
		}
		
		$pop_module_settingsprocessor_manager = PoPEngine_Module_SettingsProcessorManager_Factory::get_instance();
		$processor = $pop_module_settingsprocessor_manager->get_processor($page_id);

		// If we get an array, then it defines the value on a page by page basis
		// Otherwise, it's true/false, just return it
		$redirect_urls = $processor->get_redirect_url();
		if (is_array($redirect_urls)) {

			return $redirect_urls[$page_id];
		}

		return $redirect_urls;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPEngine_Module_SettingsManager();
