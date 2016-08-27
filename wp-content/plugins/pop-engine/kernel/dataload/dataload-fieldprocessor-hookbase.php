<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_FieldProcessor_HookBase {

	/**
	 * Function to override
	 */
	function get_fieldprocessors_to_hook() {

		return array();
	}

	function __construct() {

		foreach ($this->get_fieldprocessors_to_hook() as $fieldprocessor) {

			$filter = sprintf(GD_DATALOAD_FIELDPROCESSOR_FILTER, $fieldprocessor);
			add_filter($filter, array($this, 'hook_value'), 10, 4);
		}
	}

	function hook_value($value, $resultitem, $field, $fieldprocessor) {

		// if $value already is not an error, then another filter already could resolve this field, so return it
		if (!is_wp_error($value)) {
			return $value;
		}

		// Can this class resolve this field?
		$hook_value = $this->get_value($resultitem, $field, $fieldprocessor);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}

		return $value;
	}

	function get_value($resultitem, $field, $fieldprocessor) {
	
		return new WP_Error('no-field');
	}
}