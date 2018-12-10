<?php

class GD_DataLoad_ConvertibleFieldProcessorResolver_Base {

	function __construct($convertiblefieldprocessor_name) {

		global $gd_dataload_fieldprocessor_manager;
		$convertiblefieldprocessor = $gd_dataload_fieldprocessor_manager->get($convertiblefieldprocessor_name);
		$convertiblefieldprocessor->add_fieldprocessor_resolver($this);
	}

	function get_fieldprocessor() {

		return null;
	}

	function process($resultitem) {

		return false;
	}

	function cast($resultitem) {

		return $resultitem;
	}
}
