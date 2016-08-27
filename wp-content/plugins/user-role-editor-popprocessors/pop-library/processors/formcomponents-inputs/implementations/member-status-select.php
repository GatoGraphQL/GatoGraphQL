<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_FormInput_MemberStatus extends GD_FormInput_Select {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE => __('Active', 'ure-popprocessors'),
				GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_REJECTED => __('Rejected', 'ure-popprocessors'),
			)
		);		
		
		return $values;
	}
}
