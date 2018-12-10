<?php

class GD_DataLoad_QueryHandler {

    function __construct() {
    
		global $gd_dataload_queryhandler_manager;
		$gd_dataload_queryhandler_manager->add($this->get_name(), $this);
	}

    /**
     * Function to override
     */
    function get_name() {
    
		return null;
	}

	function prepare_query_args(&$query_args) {
		// Do nothing
	}

	function get_query_state($data_properties, $checkpoint_validation, $executed, $dbobjectids) {

		return array();
	}
	function get_query_params($data_properties, $checkpoint_validation, $executed, $dbobjectids) {

		return array();
	}
	function get_query_result($data_properties, $checkpoint_validation, $executed, $dbobjectids) {

		return array();
	}
}
