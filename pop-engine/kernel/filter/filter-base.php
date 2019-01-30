<?php
namespace PoP\Engine;

abstract class FilterBase {

	function __construct() {
	
		global $POP_FILTER_manager;
		$POP_FILTER_manager->add($this);
	}

	abstract function get_name();
	
	abstract function get_filtercomponents();

	function get_wildcard_filter() {
	
		return null;
	}

	function filter_query(&$query, $data_properties) {
	}


	function get_filter_args_override_values() {
	
		return array();
	}

	function get_filter_args() {
	
		return array();
	}
}
