<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput OrderProfile
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_OrderTag extends GD_FormInput_Order {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				'name|ASC' => __('Name ascending', 'pop-wpapi'),
				'name|DESC' => __('Name descending', 'pop-wpapi'),
				'count|DESC' => __('Count highest', 'pop-wpapi'),
				'count|ASC' => __('Count lowest', 'pop-wpapi'),
			)
		);		
		
		return $values;
	}
}
