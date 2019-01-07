<?php
namespace PoP\Engine\Settings;

abstract class SettingsProcessorBase {

	function __construct() {

		// Comment Leo 30/09/2017: Important: do it at the very end, to make sure that
		// all constants have been set by then (otherwise, in file settingsprocessor.pht 
		// it may add the configuration under page "POP_CATEGORYPOSTS_PAGE_CATEGORYPOSTS01", 
		// it is not treated as false if the constant has not been defined)
		add_action('init', array($this, 'init'), PHP_INT_MAX);
	}

	function init() {
		
		SettingsProcessorManager_Factory::get_instance()->add($this);
	}

	abstract function pages_to_process();

	// function get_checkpoint_configuration() {
	function get_checkpoints() {
	
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