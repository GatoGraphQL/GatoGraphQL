<?php
namespace PoP\Engine;
 
abstract class FieldProcessor_HookBase {

	/**
	 * Function to override
	 */
	abstract function get_fieldprocessors_to_hook();

	function __construct() {

		foreach ($this->get_fieldprocessors_to_hook() as $fieldprocessor_name) {

			$filter = sprintf(GD_DATALOAD_FIELDPROCESSOR_FIELDVALUEFILTER, $fieldprocessor_name);
			add_filter($filter, array($this, 'hook_value'), $this->get_priority(), 4);

			$filter = sprintf(GD_DATALOAD_FIELDPROCESSOR_FIELDDATALOADERFILTER, $fieldprocessor_name);
			add_filter($filter, array($this, 'hook_field_default_dataloader'), $this->get_priority(), 3);
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

	function hook_field_default_dataloader($default_dataloader, $field, $fieldprocessor) {

		// if $value already is not an error, then another filter already could resolve this field, so return it
		if ($default_dataloader) {
			return $default_dataloader;
		}

		return $this->get_field_default_dataloader($field, $fieldprocessor);
	}

	function get_value($resultitem, $field, $fieldprocessor) {
	
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$error_class = $cmsapi->get_error_class();
		return new $error_class('no-field');
	}

	function get_field_type() {

		return GD_DATALOAD_FIELDPROCESSOR_FIELDTYPE_DBDATA;
	}

	function get_field_default_dataloader($field, $fieldprocessor) {

		return null;
	}
}