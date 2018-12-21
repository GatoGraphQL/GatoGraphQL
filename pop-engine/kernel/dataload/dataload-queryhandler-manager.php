<?php
namespace PoP\Engine;

class QueryHandler_Manager {

    var $queryhandlers;
    
    function __construct() {
    
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
global $gd_dataload_queryhandler_manager;
$gd_dataload_queryhandler_manager = new QueryHandler_Manager();