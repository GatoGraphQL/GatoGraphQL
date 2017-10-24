<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Location Countries
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_EM_LocationCountries extends GD_FormInput_MultiSelect {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			em_get_countries()
		);		
		
		return $values;
	}

	function get_default_value($output=false) {

		return array(get_option('dbem_location_default_country'));
	}
}
