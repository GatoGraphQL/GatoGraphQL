<?php

class PoPEngine_Module_SettingsProcessorBase {

	function __construct() {

		// Comment Leo 30/09/2017: Important: do it at the very end, to make sure that
		// all constants have been set by then (otherwise, in file settingsprocessor.pht 
		// it may add the configuration under page "POP_CATEGORYPOSTS_PAGE_CATEGORYPOSTS01", 
		// it is not treated as false if the constant has not been defined)
		add_action('init', array($this, 'init'), PHP_INT_MAX);
	}

	function init() {

		$pop_module_settingsprocessor_manager = PoPEngine_Module_SettingsProcessorManager_Factory::get_instance();
		$pop_module_settingsprocessor_manager->add($this);
	}

	function pages_to_process() {

		return array();
	}

	/**
	 * Function to override
	 */
	function get_checkpoint_configuration() {
	
		return array();
	}

	function is_functional() {

		return false;
	}

	function is_for_internal_use() {

		return false;
	}

	function needs_target_id() {

		return false;
	}

	function get_redirect_url() {

		return null;
	}
}