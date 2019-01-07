<?php
namespace PoP\Engine;

abstract class CheckpointProcessorBase {

	function __construct() {

		CheckpointProcessor_Manager_Factory::get_instance()->add($this, $this->get_checkpoints_to_process());
	}

	abstract function get_checkpoints_to_process();

	function process($checkpoint/*, $module = null*/) {
	
		// By default, no problem at all, so always return true
		return true;
	}
}