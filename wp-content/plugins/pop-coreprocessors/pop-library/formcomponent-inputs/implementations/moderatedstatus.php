<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_ModeratedStatus extends GD_FormInput_MultiSelect {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				'draft' => __('Draft', 'pop-coreprocessors'),
				'pending' => __('Pending to be published', 'pop-coreprocessors'),
				'publish' => __('Published', 'pop-coreprocessors')
			)
		);		
		
		return $values;
	}	
}
