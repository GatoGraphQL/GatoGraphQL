<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * FilterInput Base
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent {

	function get_filterformcomponent_value() {
	
		global $gd_template_processor_manager;
		$filterformcomponent = $this->get_filterformcomponent();				
		return $gd_template_processor_manager->get_processor($filterformcomponent)->get_value($filterformcomponent);
	}

	function get_name() {
	
		return $this->get_filterformcomponent();
	}
	
	function get_filterformcomponent() {
	
		return null;
	}
	
	function get_formcomponent() {
	
		return $this->get_filterformcomponent();
	}
	
	function get_metaquery_key() {
	
		return null;
	}	
	
	function get_metaquery_compare() {
	
		return 'IN';
	}		
	
	function get_metaquery() {
	
		$meta_query = array();		
		$key = $this->get_metaquery_key();
		$value = $this->get_filterformcomponent_value();
		$compare = $this->get_metaquery_compare();
		
		// Special case for EXISTS: it can switch between EXISTS and NOT EXISTS depending on if the value is true or false
		if ($compare == 'EXISTS') {
			
			// $value can be a single value, or an array of true/false
			if (is_array($value)) {

				// Do the filtering only if there is 1 value (2 values => same as not filtering)
				if (count($value) !== 1) {

					return $meta_query;
				}

				// Only 1 value in the multiselect, extract it
				$value = $value[0];
			}

			// Do the filtering: if $value is false, then filter by NOT EXISTS
			if ($value === false) {

				$value = true;
				$compare = 'NOT EXISTS';
			}
		}
		
		if ($key && $value) {

			$meta_query[] = array(
				'key' => $key,
				'value' => $value,
				'compare' => $compare
			);			
		}
								
		return $meta_query;
	}
	
	function get_order() {
	
		return array();
	}
	
	function get_postdates() {
	
		return null;
	}

	function get_author() {
	
		return array();
	}

	function get_poststatus() {
	
		return array();
	}
	
	function get_search() {
	
		return null;
	}

	function get_tags() {
	
		return array();
	}

	function get_categories() {
	
		return array();
	}

	function get_taxonomies() {
	
		return array();
	}
}
