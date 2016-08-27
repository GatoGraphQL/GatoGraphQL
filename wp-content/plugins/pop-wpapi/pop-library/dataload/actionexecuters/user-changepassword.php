<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CHANGEPASSWORD_USER', 'changepassword-user');

class GD_DataLoad_ActionExecuter_ChangePassword_User extends GD_DataLoad_ActionExecuter {

	function get_name() {

		return GD_DATALOAD_ACTIONEXECUTER_CHANGEPASSWORD_USER;
	}

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			global $gd_changepassword_user;
			$errors = array();
			$gd_changepassword_user->changepassword($errors, $block_atts);

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

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_ChangePassword_User();