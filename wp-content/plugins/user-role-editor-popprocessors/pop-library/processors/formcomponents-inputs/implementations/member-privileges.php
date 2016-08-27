<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_FormInput_MemberPrivileges extends GD_FormInput_MultiSelect {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT => __('Contribute content', 'ure-popprocessors')
			)
		);		
		
		return $values;
	}
}
