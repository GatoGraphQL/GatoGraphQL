<?php

class PoP_Module_Processor_Manager {

	var $processors;
	
	function __construct() {
	
		$this->processors = array();
	}
	
	function get_processor($module) {
	
		$processor = $this->processors[$module];
		if (!$processor) {

			throw new Exception(sprintf('No Processor for $module \'%s\' (%s)', $module, full_url()));
		}

		return $processor;
	}
	
	function add($processor, $modules_to_process) {
	
		foreach ($modules_to_process as $module) {
		
			$this->processors[$module] = $processor;
		}	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_module_processor_manager;
$pop_module_processor_manager = new PoP_Module_Processor_Manager();
