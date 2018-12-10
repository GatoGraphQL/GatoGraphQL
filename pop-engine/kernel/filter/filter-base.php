<?php

class GD_FilterBase {

	function __construct() {
	
		global $gd_filter_manager;
		$gd_filter_manager->add($this);
	}

	function get_name() {
	
		return '';
	}
	
	function get_filtercomponents() {
	
		return array();
	}

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
