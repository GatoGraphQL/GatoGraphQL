<?php
namespace PoP\Engine;

abstract class FilterComponentBase {

	function get_filterinput_value($filter) {
	
		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		$filterinput = $this->get_filterinput();
		return $moduleprocessor_manager->get_processor($filterinput)->get_value($filterinput, $filter);
	}

	function get_name() {
	
		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		$filterinput = $this->get_filterinput();
		return $moduleprocessor_manager->get_processor($filterinput)->get_name($filterinput);
	}
	
	abstract function get_filterinput();
	
	function get_forminput() {
	
		return $this->get_filterinput();
	}

	function add_forminput_module() {

		return true;
	}
	
	function get_metaquery_key() {
	
		return null;
	}	
	
	function get_metaquery_compare() {
	
		return 'IN';
	}		
	
	function get_metaquery($filter) {
	
		$meta_query = array();		
		$key = $this->get_metaquery_key();
		$value = $this->get_filterinput_value($filter);
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
	
	function get_order($filter) {
	
		return array();
	}
	
	function get_postdates($filter) {
	
		return null;
	}

	function get_author($filter) {
	
		return array();
	}

	function get_poststatus($filter) {
	
		return array();
	}
	
	function get_search($filter) {
	
		return null;
	}

	function get_tags($filter) {
	
		return array();
	}

	function get_categories($filter) {
	
		return array();
	}

	function get_taxonomies($filter) {
	
		return array();
	}
}
