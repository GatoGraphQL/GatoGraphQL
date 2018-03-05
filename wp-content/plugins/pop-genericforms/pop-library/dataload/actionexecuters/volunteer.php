<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_VOLUNTEER', 'volunteer');

class GD_DataLoad_ActionExecuter_Volunteer extends GD_DataLoad_FormActionExecuterBase {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_VOLUNTEER;
	}

    protected function get_instance() {

		return new PoP_ActionExecuterInstance_Volunteer();
	}

    function execute_form(&$block_data_settings, $block_atts, &$block_execution_bag) {

		$errors = array();
		$instance = $this->get_instance();
		$instance->volunteer($errors, $block_atts);

		if ($errors) {

			return array(
				GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $errors
			);
		}
		
		// No errors => success
		return array(
			GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
		);
	}

 //    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

	// 	// If the post has been submitted, execute the Gravity Forms shortcode
	// 	if ('POST' == $_SERVER['REQUEST_METHOD']) {

	// 		$errors = array();
	// 		$instance = $this->get_instance();
	// 		$instance->volunteer($errors, $block_atts);

	// 		if ($errors) {

	// 			return array(
	// 				GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $errors
	// 			);
	// 		}
			
	// 		// No errors => success
	// 		return array(
	// 			GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
	// 		);	
	// 	}

	// 	return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	// }
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_Volunteer();
