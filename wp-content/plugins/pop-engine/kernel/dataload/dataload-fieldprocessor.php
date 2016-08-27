<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_DATALOAD_FIELDPROCESSOR_FILTER', 'gd_template:dataload_fieldprocessor:%s');

class GD_DataLoad_FieldProcessor {

	function __construct() {
    
		global $gd_dataload_fieldprocessor_manager;
		$gd_dataload_fieldprocessor_manager->add($this->get_name(), $this);
	}

	function get_id($resultitem) {
	
		return $resultitem->ID;		
	}
	
	function get_value($resultitem, $field) {
	
		switch ($field) {
		
			case 'id' :
			
				return $this->get_id($resultitem);																													
		}

		// Comment Leo 29/08/2014: needed for compatibility with GD_DataLoader_ConvertiblePostList
		// (So that data-fields aimed for another post_type are not retrieved)
		return new WP_Error('no-field');
	}	

	function get_hook_value($fieldprocessor, $resultitem, $field) {

		// First Check if there's a hook to implement this field
		$filter = sprintf(GD_DATALOAD_FIELDPROCESSOR_FILTER, $fieldprocessor);
		
		// Also send the fieldprocessor along, as to get the id of the $resultitem being passed
		return apply_filters($filter, new WP_Error('no-field'), $resultitem, $field, $this);
	}
	
	function get_name() {
	
		return '';
	}	

	// This is a horrible fix, but needed, because the value true is represented as "1", but we can't save value 
	// "1" in a select because of php arrays (the "1" is interpreted as the array position, and not as the key)
	// function get_yesno_select_string($resultitem, $field) {

	// 	if($this->get_value($resultitem, $field)) {
	// 		return 'true';
	// 	}
	// 	return 'false';
	// }
}
	
