<?php
namespace PoP\Engine;
 
abstract class FieldProcessor_HookBase {

	/**
	 * Function to override
	 */
	abstract function get_fieldprocessors_to_hook();

	function __construct() {

		foreach ($this->get_fieldprocessors_to_hook() as $fieldprocessor) {

			$filter = sprintf(GD_DATALOAD_FIELDPROCESSOR_FILTER, $fieldprocessor);
			add_filter($filter, array($this, 'hook_value'), $this->get_priority(), 4);
		}
	}

	function get_priority() {

		return 10;
	}

	function hook_value($value, $resultitem, $field, $fieldprocessor) {

		// if $value already is not an error, then another filter already could resolve this field, so return it
		if (!is_wp_error($value)) {
			return $value;
		}

		return $this->get_value($resultitem, $field, $fieldprocessor);
	}

	function get_value($resultitem, $field, $fieldprocessor) {
	
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$error_class = $cmsapi->get_error_class();
		return new $error_class('no-field');
	}

	function get_field_type() {

		return GD_DATALOAD_FIELDPROCESSOR_FIELDTYPE_DBDATA;
	}
}