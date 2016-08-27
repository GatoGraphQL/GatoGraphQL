<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput OrderProfile
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_OrderPost extends GD_FormInput_Order {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				'date|DESC' => __('Latest published', 'pop-wpapi'),
				'date|ASC' => __('Earliest published', 'pop-wpapi'),
				'comment_count|DESC' => __('Most comments', 'pop-wpapi'),
				'comment_count|ASC' => __('Less comments', 'pop-wpapi'),
				'title|ASC' => __('Title ascending', 'pop-wpapi'),						
				'title|DESC' => __('Title descending', 'pop-wpapi')
			)
		);		
		
		return $values;
	}
}
