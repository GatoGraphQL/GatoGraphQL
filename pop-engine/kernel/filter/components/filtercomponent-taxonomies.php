<?php
namespace PoP\Engine;

abstract class FilterComponent_TaxonomiesBase extends FilterComponentBase {
	
	function get_taxonomies($filter) {
	
		return $this->get_filterinput_value($filter);
	}	

	function get_filterinput_value($filter) {
	
		$value = array();

		// Each value in the list comes in the format "taxonomy|term", so group them all together
		if ($pairs = parent::get_filterinput_value($filter)) {

			foreach ($pairs as $pair) {

				$component = explode('|', $pair);
				$taxonomy = $component[0];
				$term = $component[1];
				if (!$value[$taxonomy]) {
					$value[$taxonomy] = array();
				}
				$value[$taxonomy][] = $term;
			}
		}
				
		return $value;
	}
	
}
