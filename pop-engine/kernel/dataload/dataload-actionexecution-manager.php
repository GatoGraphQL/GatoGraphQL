<?php

class GD_DataLoad_ActionExecution_Manager {

    var $executioners, $results;
    
    function __construct() {

    	GD_DataLoad_ActionExecution_Manager_Factory::set_instance($this);
    
		$this->executioners = $this->results = array();

		add_action(
			'PoP_Engine:generate_data:reset',
			array($this, 'reset')
		);
	}

	function reset() {

    	$this->results = array();
	}
	
    function add_actionexecutioner($name, $executioner) {
    
		$this->executioners[$name] = $executioner;
	}
	
    function get_actionexecuter($name) {
    
		$actionexecuter = $this->executioners[$name];
		if (!$actionexecuter) {

			throw new Exception(sprintf('No Action Executer with name \'%s\' (%s)', $name, full_url()));
		}

		return $actionexecuter;
	}
	
    function set_result($name, $result) {
    
		$this->results[$name] = $result;
	}
	
    function get_result($name) {
    
		return $this->results[$name];
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecution_Manager();