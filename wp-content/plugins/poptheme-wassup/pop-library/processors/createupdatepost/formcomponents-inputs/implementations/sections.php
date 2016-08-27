<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Categories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_Sections extends GD_FormInput_MultiSelect {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);

		// The sections are received in this format:
		// array('taxonomy' => 'terms')
		// To print it in the forminput, it must be transformed to a list with this format:
		// array('taxonomy|term')
		$formatted = array();
		$section_taxonomyterms = apply_filters('wassup_section_taxonomyterms', array());
		foreach ($section_taxonomyterms as $taxonomy => $terms) {
			foreach ($terms as $term) {
				$item = $taxonomy.'|'.$term;
				$formatted[$item] = apply_filters('GD_FormInput_Sections:taxonomyterms:name', $item, $taxonomy, $term);
			}
		}
		
		$values = array_merge(	
			$values, 
			$formatted
		);		
		
		return $values;
	}		
}
