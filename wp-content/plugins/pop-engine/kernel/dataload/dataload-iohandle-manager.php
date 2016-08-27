<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// define ('GD_DATALOAD_IOHANDLER_DEFAULT', 'default');

class GD_DataLoad_IOHandle_Manager {

    var $iohandlers;
    
    function __construct() {
    
		return $this->iohandlers = array();
	}
	
    function add($name, $iohandler) {
    
		$this->iohandlers[$name] = $iohandler;
	}
	
	function get($name) {

		// Return the iohandlers if it exists
		if ($this->iohandlers[$name]) {
		
			return $this->iohandlers[$name];
		};
		
		return null;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_dataload_iohandle_manager;
$gd_dataload_iohandle_manager = new GD_DataLoad_IOHandle_Manager();