<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_USERAVATAR_UPDATE', 'useravatar-update');

class GD_DataLoad_ActionExecuter_UserAvatar_Update extends GD_DataLoad_ActionExecuter {

	function get_name() {

		return GD_DATALOAD_ACTIONEXECUTER_USERAVATAR_UPDATE;
	}

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			global $gd_useravatar_update;
			$errors = array();
			$gd_useravatar_update->save($errors, $block_atts);

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
new GD_DataLoad_ActionExecuter_UserAvatar_Update();