<?php
namespace PoP\Engine;

class QueryHandler_Manager {

    var $queryhandlers;
    
    function __construct() {
    
    	QueryHandler_Manager_Factory::set_instance($this);    
		return $this->queryhandlers = array();
	}
	
    function add($name, $queryhandler) {
    
		$this->queryhandlers[$name] = $queryhandler;
	}
	
	function get($name) {

		$queryhandler = $this->queryhandlers[$name];
		if (!$queryhandler) {

			throw new \Exception(sprintf('No QueryHandler with name \'%s\' (%s)', $name, full_url()));
		}

		return $queryhandler;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new QueryHandler_Manager();