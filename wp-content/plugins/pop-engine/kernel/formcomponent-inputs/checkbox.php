<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput MultipleOptions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_Checkbox extends GD_FormInput {

	function get_selectedvalue_from_request() {

		// For the checkbox, the value is true not if its value in the request is true,
		// but if they key has been set at all (checked: sends the attribute. unchecked: sends nothing)
		if (isset($_REQUEST[$this->get_name()])) {
			return true;
		}
		return '';
	}

	// function get_default_value($conf) {
	
	// 	return false;
	// }
}