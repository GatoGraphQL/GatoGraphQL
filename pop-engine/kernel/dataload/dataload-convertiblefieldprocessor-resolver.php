<?php
namespace PoP\Engine;

class ConvertibleFieldProcessorResolverBase {

	function __construct($convertiblefieldprocessor_name) {

		$fieldprocessor_manager = FieldProcessor_Manager_Factory::get_instance();
		$convertiblefieldprocessor = $fieldprocessor_manager->get($convertiblefieldprocessor_name);
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
