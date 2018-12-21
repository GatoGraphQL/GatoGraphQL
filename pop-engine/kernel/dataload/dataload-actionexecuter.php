<?php
namespace PoP\Engine;

class ActionExecuter {

	function __construct() {
    
		$gd_dataload_actionexecution_manager = ActionExecution_Manager_Factory::get_instance();
		$gd_dataload_actionexecution_manager->add_actionexecutioner($this->get_name(), $this);
	}

    function get_name() {
    
		return '';
	}

    function execute(&$data_properties) {
    
		return null;
	}
}
