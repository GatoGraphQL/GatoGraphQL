<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput ArticleCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_OrganizationTypes extends GD_FormInput_MultiSelect {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);

		$types = array(
			sanitize_title('Charity') => __('Charity', 'poptheme-wassup-sectionprocessors'),
			sanitize_title('Communities') => __('Communities', 'poptheme-wassup-sectionprocessors'),
			sanitize_title('Company or Corporate') => __('Company or Corporate', 'poptheme-wassup-sectionprocessors'),
			sanitize_title('Government') => __('Government', 'poptheme-wassup-sectionprocessors'),
			sanitize_title('Education') => __('Education', 'poptheme-wassup-sectionprocessors'),
			sanitize_title('NGO/NPO') => __('NGO/NPO', 'poptheme-wassup-sectionprocessors'),
			sanitize_title('Social Enterprise') => __('Social Enterprise', 'poptheme-wassup-sectionprocessors'),
			sanitize_title('Youth') => __('Youth', 'poptheme-wassup-sectionprocessors'),
		);

		$values = array_merge(	
			$values, 
			apply_filters('wassup_organizationtypes', $types)
		);	
		
		return $values;
	}		
}
