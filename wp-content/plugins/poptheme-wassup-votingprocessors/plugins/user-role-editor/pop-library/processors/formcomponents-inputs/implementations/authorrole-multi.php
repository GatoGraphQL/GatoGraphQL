<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_FormInput_MultiAuthorRole extends GD_FormInput_MultiSelect {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values[GD_URE_ROLE_ORGANIZATION] = __('Organizations', 'poptheme-wassup-votingprocessors');
		$values[GD_URE_ROLE_INDIVIDUAL] = __('Individuals', 'poptheme-wassup-votingprocessors');
		
		return $values;
	}	
}
