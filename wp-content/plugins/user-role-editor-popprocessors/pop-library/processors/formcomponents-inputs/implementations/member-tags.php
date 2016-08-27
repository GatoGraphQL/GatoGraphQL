<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_FormInput_MemberTags extends GD_FormInput_MultiSelect {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERTAGS_MEMBER => __('Member', 'ure-popprocessors'),
				'staff' => __('Staff', 'ure-popprocessors'),
				'volunteer' => __('Volunteer', 'ure-popprocessors'),
				'student' => __('Student', 'ure-popprocessors'),
				'teacher/lecturer' => __('Teacher/Lecturer', 'ure-popprocessors'),
				'unknown' => __('Unknown', 'ure-popprocessors'),
			)
		);		
		
		return $values;
	}
}
