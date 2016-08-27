<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput OrderProfile
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_OrderUser extends GD_FormInput_Order {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				'display_name|ASC' => __('Name ascending', 'pop-wpapi'),
				'display_name|DESC' => __('Name descending', 'pop-wpapi'),
				'post_count|DESC' => __('Most active', 'pop-wpapi'),
				'post_count|ASC' => __('Less active', 'pop-wpapi'),
				'registered|DESC' => __('Recently registered', 'pop-wpapi'),						
				'registered|ASC' => __('Early registered', 'pop-wpapi')
			)
		);		
		
		return $values;
	}
}
