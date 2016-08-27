<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_SETTINGS', 'actionexecuter-settings');

class GD_DataLoad_ActionExecuter_Settings extends GD_DataLoad_ActionExecuter {

	var $fieldoperators;
    
    function __construct() {
    
		parent::__construct();
		return $this->fieldoperators = array();
	}
	
    // These values must be injected from outside
    function add($field, $operator) {
    
		// each operator must be of class GD_Settings_UrlOperator
		$this->fieldoperators[$field] = $operator;
	}

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_SETTINGS;
	}

	function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		// If the post has been submitted, execute the Gravity Forms shortcode
		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			global $gd_template_processor_manager;

			// Return the redirect. Use Hard redirect
			// $redirect_to = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_BROWSERURL)->get_value(GD_TEMPLATE_FORMCOMPONENT_BROWSERURL, $block_atts);
			// if (!$redirect_to) {
				
			// 	$redirect_to = home_url();
			// }
			// Comment Leo 22/05/2015: If we forward to the same URL but with different lang, it will always go to https://www.mesym.com/ms/settings/
			// So forward to the homepage instead (temporary solution)
			// $url = qtranxf_convertURL($url, $value);
			// Using get_site_url() instead of home_url() so that it doesn't include the language bit, which will be changed later on
			$redirect_to = get_site_url();

			// Add all the params selected by the user
			foreach ($this->fieldoperators as $field => $operator) {

				$value = trim($gd_template_processor_manager->get_processor($field)->get_value($field, $block_atts));
				$redirect_to = $operator->get_url($redirect_to, $field, $value);
			}

			return array(
				GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true,
				GD_DATALOAD_IOHANDLER_FORM_HARDREDIRECT => $redirect_to
			);
		}

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_Settings();