<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_LinkAccess extends GD_FormInput_MultiSelect {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				'free' => __('Free access', 'poptheme-wassup'),
				// 'paywall' => __('Behind a paywall (eg: Malaysiakini)', 'poptheme-wassup'),
				'paywall' => __('Behind a paywall', 'poptheme-wassup'),
				// 'walledgarded' => __('Behind a walled garden (eg: Facebook)', 'poptheme-wassup')
				'walledgarded' => __('User account needed', 'poptheme-wassup')
			)
		);		
		
		return $values;
	}	
}
