<?php
namespace PoP\Engine;

abstract class PageModuleProcessorBase {

	function __construct() {

		// Comment Leo 30/09/2017: Important: do it at the very end, to make sure that
		// all constants have been set by then (otherwise, in file settingsprocessor.pht 
		// it may add the configuration under page "POP_CATEGORYPOSTS_PAGE_CATEGORYPOSTS01", 
		// it is not treated as false if the constant has not been defined)
		add_action('init', array($this, 'init'), PHP_INT_MAX);
	}

	function init() {

		$pop_module_pagemoduleprocessor_manager = PageModuleProcessorManager_Factory::get_instance();
		$pop_module_pagemoduleprocessor_manager->add($this);
	}

	/**
	 * Function to override
	 */
	function get_groups() {

		return array();
	}

	/**
	 * Function to override
	 */
	function get_page_modules_by_vars_properties() {
	
		return array();
	}

	/**
	 * Function to override
	 */
	function get_nopage_modules_by_vars_properties() {

		return array();
	}
}