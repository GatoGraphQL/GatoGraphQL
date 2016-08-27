<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput ArticleCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_Taxonomy extends GD_FormInput_MultiSelect {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values["1"] = __('Yes', 'greendrinks');
		$values["-1"] = __('No', 'greendrinks');
		
		return $values;
	}	
	
	function get_default_value() {
	
		return array();
	}		
}
