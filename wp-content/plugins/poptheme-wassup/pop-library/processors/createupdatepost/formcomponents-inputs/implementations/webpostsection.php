<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Categories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_WebPostSection extends GD_FormInput_Select {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);

		foreach (PoPTheme_Wassup_Utils::get_webpostsection_cats() as $cat) {
			$values[$cat] = apply_filters('GD_FormInput_WebPostSections:cat:name', gd_get_categoryname($cat), $cat);
		}
		
		return $values;
	}		
}
