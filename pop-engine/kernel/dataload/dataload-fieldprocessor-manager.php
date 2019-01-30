<?php
namespace PoP\Engine;

class FieldProcessor_Manager {

    var $fieldprocessors;
    
    function __construct() {
    
    	FieldProcessor_Manager_Factory::set_instance($this);    
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
new FieldProcessor_Manager();