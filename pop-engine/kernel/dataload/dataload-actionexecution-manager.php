<?php
namespace PoP\Engine;

class ActionExecution_Manager {

    var $executioners, $results;
    
    function __construct() {

    	ActionExecution_Manager_Factory::set_instance($this);    
		$this->executioners = $this->results = array();

		add_action(
			'\PoP\Engine\Engine:generate_data:reset',
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

			throw new \Exception(sprintf('No Action Executer with name \'%s\' (%s)', $name, full_url()));
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
new ActionExecution_Manager();