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
	
	// Return only the settings
	function get_formatted_data($settings, $runtimesettings, $crawlableitems, $runtimecrawlableitems, $data) {
	
		return array('json' => json_encode($data));
	}

}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_DataStructureFormatter_Data();

