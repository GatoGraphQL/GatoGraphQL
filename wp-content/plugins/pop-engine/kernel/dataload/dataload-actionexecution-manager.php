<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoad_ActionExecution_Manager {

    var $executioners;
    
    function __construct() {
    
		return $this->executioners = array();
	}
	
    function add($name, $executioner) {
    
		$this->executioners[$name] = $executioner;
	}
	
    function get_actionexecuter($name) {
    
		return $this->executioners[$name];
	}
	
	function execute($name, &$block_data_settings, $block_atts, &$block_execution_bag) {

		// Return the iohandlers if it exists
		if ($this->executioners[$name]) {
		
			return $this->executioners[$name]->execute($block_data_settings, $block_atts, $block_execution_bag);
		};
		
		return null;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_dataload_actionexecution_manager;
$gd_dataload_actionexecution_manager = new GD_DataLoad_ActionExecution_Manager();