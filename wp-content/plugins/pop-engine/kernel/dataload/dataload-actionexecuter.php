<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoad_ActionExecuter {

	function __construct() {
    
		global $gd_dataload_actionexecution_manager;
		$gd_dataload_actionexecution_manager->add($this->get_name(), $this);
	}

    function get_name() {
    
		return '';
	}

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {
    
		return null;
	}
}
