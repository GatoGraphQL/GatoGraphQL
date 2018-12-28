<?php
namespace PoP\Engine;

abstract class DataStructureFormatterBase {

	function __construct() {
    
		global $gd_dataload_datastructureformat_manager;
		$gd_dataload_datastructureformat_manager->add($this->get_name(), $this);
	}
	
	abstract function get_name();
	
	function get_formatted_data($data) {
	
		return $data;
	}
	
	function get_json_encode_type() {
	
		return null;
	}
	
	function get_dataitem($data_fields, $resultitem, $fieldprocessor) {

		$fieldprocessor_name = $fieldprocessor->get_name();
		
		$dataitem = array();
		foreach ($data_fields as $field) {

			$value = $fieldprocessor->get_value($resultitem, $field);

			// Comment Leo 29/08/2014: needed for compatibility with Dataloader_ConvertiblePostList
			// (So that data-fields aimed for another post_type are not retrieved)
			if (!is_wp_error($value)) {
				$dataitem[$field] = $value;
			}
		}
		
		return $dataitem;
	}
	
	// Add dataitem to dataset
	function add_to_dataitems(&$dataitems, $id, $data_fields, $resultitem, $fieldprocessor) {

		$dataitem = $this->get_dataitem($data_fields, $resultitem, $fieldprocessor);
		
		// Place under the ID, so it can be found in the database
		$dataitems[$id] = $dataitem;

		return $dataitem;
	}	
}
	
