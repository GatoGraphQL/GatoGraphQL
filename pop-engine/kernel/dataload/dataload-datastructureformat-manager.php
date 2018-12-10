<?php
 
class GD_DataLoad_DataStructureFormat_Manager {

    var $formatters;
    var $default_name;
    
    function __construct() {
    
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
global $gd_dataload_datastructureformat_manager;
$gd_dataload_datastructureformat_manager = new GD_DataLoad_DataStructureFormat_Manager();