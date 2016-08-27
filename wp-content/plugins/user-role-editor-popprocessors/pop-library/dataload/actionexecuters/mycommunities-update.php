<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_UPDATE_MYCOMMUNITIES', 'update-mycommunities');

class GD_DataLoad_ActionExecuter_Update_MyCommunities extends GD_DataLoad_ActionExecuter {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_UPDATE_MYCOMMUNITIES;
	}

	function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			global $gd_update_mycommunities;
			$errors = array();
			$updated = $gd_update_mycommunities->update($errors, $block_atts);

			// Allow for both success and errors (eg: some organizations added, others not since they banned the user)
			$ret = array();
			if ($errors) {
				$ret[GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS] = $errors;
			}
			if ($updated) {
				$ret[GD_DATALOAD_IOHANDLER_FORM_SUCCESS] = true;
			}

			return $ret;
		}

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_Update_MyCommunities();