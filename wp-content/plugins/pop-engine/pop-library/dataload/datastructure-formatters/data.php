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
	function get_formatted_data($settings, $runtimesettings, $sitemapping, $data) {
	
		// return array('json' => json_encode($data));
		return array('json' => $data);
	}

}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_DataStructureFormatter_Data();

