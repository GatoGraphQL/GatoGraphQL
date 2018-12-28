<?php
namespace PoP\Engine;

abstract class ActionExecuterBase {

	function __construct() {
    
		$gd_dataload_actionexecution_manager = ActionExecution_Manager_Factory::get_instance();
		$gd_dataload_actionexecution_manager->add_actionexecutioner($this->get_name(), $this);
	}

    abstract function get_name();

    function execute(&$data_properties) {
    
		return null;
	}
}
