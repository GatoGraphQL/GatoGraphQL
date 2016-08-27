<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_SettingsFormat extends GD_FormInput_Select {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				GD_TEMPLATEFORMAT_SIMPLEVIEW => __('Feed', 'poptheme-wassup'),
				GD_TEMPLATEFORMAT_FULLVIEW => __('Extended feed', 'poptheme-wassup'),
				GD_TEMPLATEFORMAT_MAP => __('Map', 'poptheme-wassup'),
				GD_TEMPLATEFORMAT_DETAILS => __('Details', 'poptheme-wassup'),
				GD_TEMPLATEFORMAT_THUMBNAIL => __('Thumbnail', 'poptheme-wassup'),
				GD_TEMPLATEFORMAT_LIST => __('List', 'poptheme-wassup'),
			)
		);		
		
		return $values;
	}	
	
	function get_default_value($conf) {

		$vars = GD_TemplateManager_Utils::get_vars();
		if ($selected = $vars['settingsformat']) {

			$allvalues = array(
				GD_TEMPLATEFORMAT_SIMPLEVIEW,
				GD_TEMPLATEFORMAT_FULLVIEW,
				GD_TEMPLATEFORMAT_MAP,
				GD_TEMPLATEFORMAT_DETAILS,
				GD_TEMPLATEFORMAT_THUMBNAIL,
				GD_TEMPLATEFORMAT_LIST,
			);
			if (in_array($selected, $allvalues)) {

				return $selected;
			}
		}
	
		return GD_TEMPLATEFORMAT_SIMPLEVIEW;
	}
}
