<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_YesNo extends GD_FormInput_Select {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				'true' => __('Yes', 'pop-coreprocessors'),
				'false' => __('No', 'pop-coreprocessors'),
			)
		);		
		
		return $values;
	}	
	
	function get_default_value($conf) {
	
		return false;
	}

	function get_value($conf) {

		// Return false / true bools
		$value = parent::get_value($conf);
		if ($value == 'true') {
			return true;
		}

		return false;
	}

	function get_output_value($conf) {

		// Return the parent => return values as string instead of boolean, so we can use javascript .indexOf function to load correctly selected values
		return parent::get_value($conf, true);
	}	
}
