<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_EDITMEMBERSHIP', 'editmembership');

class GD_DataLoad_ActionExecuter_EditMembership extends GD_DataLoad_ActionExecuter {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_EDITMEMBERSHIP;
	}

	function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			global $gd_editmembership;
			$errors = array();
			$gd_editmembership->execute($errors, $block_atts);

			if ($errors) {

				return array(
					GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $errors
				);
			}
			
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
new GD_DataLoad_ActionExecuter_EditMembership();