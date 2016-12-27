<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_SettingsProcessorBase {

	function __construct() {

		add_action('init', array($this, 'init'));
	}

	function init() {

		global $gd_template_settingsprocessor_manager;
		$gd_template_settingsprocessor_manager->add($this/*, $this->get_pages_to_process()*/);
	}

	function process_pages($hierarchy) {

		return array();
	}

	/**
	 * Function to override
	 */
	function get_page_blockgroups($hierarchy, $include_common = true) {
	
		return array();
	}

	/**
	 * Function to override
	 */
	function get_page_blocks($hierarchy, $include_common = true) {
	
		return array();
	}

	/**
	 * Function to override
	 */
	function get_checkpoints($hierarchy) {
	
		return array();
	}

	function silent_document($hierarchy) {

		// Silent document? (Opposite to Update the browser URL and Title?)
		// Not silent by default. Yes if it is quickview, or if in the customsettings it said so for that page (eg: Loggedinuser-data)
		return false;
	}

	function is_appshell($hierarchy) {

		return false;
	}

	function store_local($hierarchy) {

		return false;
	}
}