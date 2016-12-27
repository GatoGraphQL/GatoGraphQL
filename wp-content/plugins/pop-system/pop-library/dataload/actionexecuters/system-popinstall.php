<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_SYSTEM_POPINSTALL', 'system-popinstall');

class GD_DataLoad_ActionExecuter_SystemPoPInstall extends GD_DataLoad_ActionExecuter {

	function get_name() {

		return GD_DATALOAD_ACTIONEXECUTER_SYSTEM_POPINSTALL;
	}

	function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		global $pop_engine;
		$pop_engine->install(true);

		$success_msg = __('PoP:install executed.', 'pop-system');
		return array(
			GD_DATALOAD_IOHANDLER_FORM_SUCCESSSTRINGS => array($success_msg), 
			GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
		);			
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_SystemPoPInstall();