<?php

class GD_Dataload_CheckpointProcessor_Manager {

	var $processors;
	
	function __construct() {
	
		$this->processors = array();
	}
	
	function get_processor($checkpoint) {
	
		return $this->processors[$checkpoint];
	}

	function process($checkpoint, $module = null) {

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
global $gd_dataload_checkpointprocessor_manager;
$gd_dataload_checkpointprocessor_manager = new GD_Dataload_CheckpointProcessor_Manager();
