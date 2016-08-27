<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_UnmoderatedStatusDescription extends GD_FormInput_Select {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				'draft' => __('Draft (still editing)', 'pop-coreprocessors'),
				'publish' => __('Already published', 'pop-coreprocessors')
			)
		);		
		
		return $values;
	}	
	
	function get_default_value() {
	
		return array('draft');
	}		
}
