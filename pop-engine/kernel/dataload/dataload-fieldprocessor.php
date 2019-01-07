<?php
namespace PoP\Engine;
 
abstract class FieldProcessorBase {

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

		// Comment Leo 29/08/2014: needed for compatibility with Dataloader_ConvertiblePostList
		// (So that data-fields aimed for another post_type are not retrieved)
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$error_class = $cmsapi->get_error_class();
		return new $error_class('no-field');
	}	

	function get_hook_value($fieldprocessor, $resultitem, $field) {

		// First Check if there's a hook to implement this field
		$filter = sprintf(GD_DATALOAD_FIELDPROCESSOR_FILTER, $fieldprocessor);
		
		// Also send the fieldprocessor along, as to get the id of the $resultitem being passed
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$error_class = $cmsapi->get_error_class();
		return apply_filters($filter, new $error_class('no-field'), $resultitem, $field, $this);
	}
	
	abstract function get_name();
}
	
