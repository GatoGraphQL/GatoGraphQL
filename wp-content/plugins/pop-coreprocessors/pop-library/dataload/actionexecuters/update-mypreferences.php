<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_MYPREFERENCES_UPDATE', 'mypreferences-update');

class GD_DataLoad_ActionExecuter_UpdateMyPreferences extends GD_DataLoad_ActionExecuter {

	function get_name() {

		return GD_DATALOAD_ACTIONEXECUTER_MYPREFERENCES_UPDATE;
	}

	function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		global $gd_updatemypreferences;
		$errors = array();
		$gd_updatemypreferences->execute($errors, $block_atts);

		if ($errors) {

			return array(
				GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $errors
			);
		}
		
		$success_msg = __('You have successfully updated your preferences.', 'pop-coreprocessors');

		// No errors => success
		return array(
			GD_DATALOAD_IOHANDLER_FORM_SUCCESSSTRINGS => array($success_msg), 
			GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
		);			
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_UpdateMyPreferences();