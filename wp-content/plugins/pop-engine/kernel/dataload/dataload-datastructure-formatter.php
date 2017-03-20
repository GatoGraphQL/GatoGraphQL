<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_DataStructureFormatter {

	function __construct() {
    
		global $gd_dataload_datastructureformat_manager;
		$gd_dataload_datastructureformat_manager->add($this->get_name(), $this);
	}
	
	function get_name() {
	
		return '';
	}
	
	function get_formatted_data($settings, $runtimesettings, $crawlableitems, $runtimecrawlableitems, $data) {
	
		return array();
	}
	
	function get_dataitem($data_fields, $resultitem, $fieldprocessor) {

		$fieldprocessor_name = $fieldprocessor->get_name();
		
		$dataitem = array();
		$crawlable_data = array();
		foreach ($data_fields as $field) {

			$value = $fieldprocessor->get_value($resultitem, $field);

			// Comment Leo 29/08/2014: needed for compatibility with GD_DataLoader_ConvertiblePostList
			// (So that data-fields aimed for another post_type are not retrieved)
			if (!is_wp_error($value)) {
				$dataitem[$field] = $value;
			}
		}
		$dataitem = apply_filters('gd_dataload-'.$fieldprocessor_name.'-extract_data', $dataitem, $resultitem);
		
		return $dataitem;
	}
	
	// Add dataitem to dataset
	function add_to_dataset(&$dataset, $id, $data_fields, $resultitem, $fieldprocessor) {

		$dataitem = $this->get_dataitem($data_fields, $resultitem, $fieldprocessor);
		
		// Place under the ID, so it can be found in the database
		$dataset[$id] = $dataitem;

		return $dataitem;
	}	
}
	
