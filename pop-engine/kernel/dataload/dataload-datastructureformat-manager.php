<?php
namespace PoP\Engine;
 
class DataStructureFormat_Manager {

    var $formatters;
    var $default_name;
    
    function __construct() {
    
    	DataStructureFormat_Manager_Factory::set_instance($this);    
		return $this->formatters = array();
	}
	
    function add($name, $formatter) {

		$this->formatters[$name] = $formatter;
	}

	function set_default(&$formatter) {

		$this->default_name = $formatter->get_name();
	}
	
	function get_datastructure_formatter($name) {

		// Return the formatter if it exists
		if ($this->formatters[$name]) {
		
			return $this->formatters[$name];
		};
		
		// Return the default one
		if ($this->default_name) {

			return $this->formatters[$this->default_name];
		}

		return null;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new DataStructureFormat_Manager();