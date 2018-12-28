<?php
namespace PoP\Engine;

class ModuleProcessor_Manager {

	var $processors;
	
	function __construct() {
	
		ModuleProcessor_Manager_Factory::set_instance($this);
		$this->processors = array();
	}
	
	function get_processor($module) {
	
		$processor = $this->processors[$module];
		if (!$processor) {

			throw new \Exception(sprintf('No Processor for $module \'%s\' (%s)', $module, full_url()));
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
new ModuleProcessor_Manager();
