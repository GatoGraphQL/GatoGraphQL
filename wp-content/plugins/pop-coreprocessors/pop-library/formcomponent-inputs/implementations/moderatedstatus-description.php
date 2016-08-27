<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_ModeratedStatusDescription extends GD_FormInput_Select {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				// 'draft' => __('Draft (still editing, it will not show up in the website)', 'pop-coreprocessors'),
				// 'pending' => __('Finished editing (request website admins to publish it)', 'pop-coreprocessors'),
				// 'publish' => __('Already published (by website admins)', 'pop-coreprocessors')
				'draft' => __('Draft (still editing)', 'pop-coreprocessors'),
				'pending' => __('Finished editing', 'pop-coreprocessors'),
				'publish' => __('Already published', 'pop-coreprocessors')
			)
		);		
		
		return $values;
	}	
	
	function get_default_value() {
	
		return array('draft');
	}		
}
