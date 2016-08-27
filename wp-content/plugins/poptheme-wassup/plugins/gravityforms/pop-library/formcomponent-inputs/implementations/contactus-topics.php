<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput ArticleCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_ContactUs_Topics extends GD_FormInput_MultiSelect {
	
	function get_all_values($label = null) {

		// $values = parent::get_all_values(__("Please select Topic", "greendrinks"));
		$values = parent::get_all_values($label);
		
		$topics = apply_filters(
			'gd_gf_contactus_topics',
			array(
				__('General', 'poptheme-wassup'),
				__('Website', 'poptheme-wassup'),
				__('Others', 'poptheme-wassup'),
			)
		);
		$values = array_merge(	
			$values, 
			gd_build_select_options($topics)
		);		
		
		return $values;
	}		
}
