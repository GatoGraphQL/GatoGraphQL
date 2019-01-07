<?php
namespace PoP\Engine;

class CheckpointProcessor_Manager {

	var $processors;
	
	function __construct() {
	
    	CheckpointProcessor_Manager_Factory::set_instance($this);    
		$this->processors = array();
	}
	
	function get_processor($checkpoint) {
	
		return $this->processors[$checkpoint];
	}

	function process($checkpoint/*, $module = null*/) {

		$processor = $this->get_processor($checkpoint);
		return $processor->process($checkpoint, $module);
	}
	
	function add($processor, $checkpoints_to_process) {
	
		foreach ($checkpoints_to_process as $checkpoint) {
		
			$this->processors[$checkpoint] = $processor;
		}	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new CheckpointProcessor_Manager();
