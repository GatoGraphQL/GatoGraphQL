<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_FIELDPROCESSOR_LOCATIONS', 'locations');
 
class GD_DataLoad_FieldProcessor_Locations extends GD_DataLoad_FieldProcessor {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_LOCATIONS;
	}

	function get_value($resultitem, $field) {

		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_LOCATIONS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}	
	
		$location = $resultitem;	
					
		switch ($field) {
		
			case 'id' :
				$value = $this->get_id($location);
				break;
			
			case 'coordinates' :
				$value = array(
					'lat' => $location->location_latitude,
					'lng' => $location->location_longitude
				);
				break;

			case 'name' :
				$value = $location->output('#_LOCATIONNAME');
				break;

			case 'address' :
				$value = $location->output('#_LOCATIONFULLLINE');
				break;

			case 'city' :
				$value = $location->output('#_LOCATIONTOWN');
				break;
		
			default:
				$value = parent::get_value($resultitem, $field);
				break;																														
		}

		return $value;
	}	

	function get_id($resultitem) {
	
		return $resultitem->post_id;		
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Locations();