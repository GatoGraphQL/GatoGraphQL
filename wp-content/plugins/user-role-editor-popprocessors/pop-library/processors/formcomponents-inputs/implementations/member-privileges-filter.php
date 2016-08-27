<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_FormInput_FilterMemberPrivileges extends GD_URE_FormInput_MemberPrivileges {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		// Add the 'none' value
		$values[GD_METAVALUE_NONE] = __('(None)', 'ure-popprocessors');
		
		return $values;
	}
}
