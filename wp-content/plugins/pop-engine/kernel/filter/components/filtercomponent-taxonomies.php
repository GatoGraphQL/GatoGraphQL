<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Base Typeahead
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_TaxonomiesBase extends GD_FilterComponent {
	
	function get_taxonomies() {
	
		return $this->get_filterformcomponent_value();
	}	

	function get_filterformcomponent_value() {
	
		// Each value in the list comes in the format "taxonomy|term", so group them all together
		$pairs = parent::get_filterformcomponent_value();

		$value = array();
		foreach ($pairs as $pair) {

			$component = explode('|', $pair);
			$taxonomy = $component[0];
			$term = $component[1];
			if (!$value[$taxonomy]) {
				$value[$taxonomy] = array();
			}
			$value[$taxonomy][] = $term;
		}
				
		return $value;
	}
	
}
