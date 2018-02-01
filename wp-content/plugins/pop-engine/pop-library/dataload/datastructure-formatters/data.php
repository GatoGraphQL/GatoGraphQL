<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Typeahead Formatting
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_DATASTRUCTURE_DATA', 'data');

class GD_DataLoad_DataStructureFormatter_Data extends GD_DataLoad_DataStructureFormatter {

	function get_name() {
	
		return GD_DATALOAD_DATASTRUCTURE_DATA;
	}
	
	// Return only the data
	function get_formatted_settings($settings, $runtimesettings, $sitemapping) {
	
		return array();
	}
	function get_formatted_data($data) {
	
		return $data;
	}

}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_DataStructureFormatter_Data();

