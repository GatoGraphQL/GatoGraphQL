<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_MultiYesNo extends GD_FormInput_MultiSelect {
	
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
	
	function get_value($conf) {

		// Return false / true bools
		$value = array();
		foreach (parent::get_value($conf) as $v) {

			if ($v == 'true') {
				$value[] = true;
			}
			elseif ($v == 'false') {
				$value[] = false;
			}	
		}
		
		return $value;
	}	

	function get_output_value($conf) {

		// Return the parent => return values as string instead of boolean, so we can use javascript .indexOf function to load correctly selected values
		return parent::get_value($conf, true);
	}	
}
