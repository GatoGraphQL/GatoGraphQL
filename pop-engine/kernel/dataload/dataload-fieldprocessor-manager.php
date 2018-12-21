<?php
namespace PoP\Engine;

class FieldProcessor_Manager {

    var $fieldprocessors;
    
    function __construct() {
    
		return $this->fieldprocessors = array();
	}
	
    function add($name, $fieldprocessor) {
    
		$this->fieldprocessors[$name] = $fieldprocessor;
	}
	
	function get($name) {

		return $this->fieldprocessors[$name];
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_dataload_fieldprocessor_manager;
$gd_dataload_fieldprocessor_manager = new FieldProcessor_Manager();