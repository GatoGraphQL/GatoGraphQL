<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInputs
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput {

	var $name, $filter, $selected;

	function __construct($params = array()) {

		global $gd_filter_manager;
	
		$this->name = $params['name'];
		$this->filter = $params['filter'];

		$selected = '';
		if (isset($params['selected'])) {

			$selected = $params['selected'];
		}
		elseif (!$this->filter || ($this->filter && $gd_filter_manager->filteringby($this->filter))) {

			$selected = $this->get_selectedvalue_from_request();
		}
		$this->selected = $selected;
	}

	function get_selectedvalue_from_request() {

		return $_REQUEST[$this->get_name()];
	}

	function get_name() {

		return $this->name;
	}

	/**
	 * $_REQUEST has priority (for when editing post / user data, after submitting form this will override original post / user metadata values)
	 */	
	function get_value(/*$conf, */$output=false) {

		if ($this->selected) {
		
			return $this->selected;
		}
		
		return $this->get_default_value(/*$conf*/);
	}

	/**
	 * Function to write the value to html output (needed to convert array of booleans to array of strings, so to make it easier to javascript functions)
	 * Eg: yesno.php needs to provide values ('true', 'false') instead of (true, false), so the MultiSelect can pre-select the values
	 */
	function get_output_value(/*$conf*/) {

		return $this->get_value(/*$conf*/);
	}	
	
	/**
	 * Function to override
	 */
	function get_default_value(/*$conf*/) {
	
		return '';
	}

	// function get_output_default_value(/*$conf*/) {
	
	// 	return $this->get_default_value(/*$conf*/);
	// }
}