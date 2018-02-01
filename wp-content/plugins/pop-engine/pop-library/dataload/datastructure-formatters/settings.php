<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Typeahead Formatting
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_DATASTRUCTURE_SETTINGS', 'settings');

class GD_DataLoad_DataStructureFormatter_Settings extends GD_DataLoad_DataStructureFormatter {

	function get_name() {
	
		return GD_DATALOAD_DATASTRUCTURE_SETTINGS;
	}
	
	// Return only the settings
	function get_formatted_settings($settings, $runtimesettings, $sitemapping) {
	
		return $settings;
	}
	function get_formatted_data($data) {
	
		return array();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_DataStructureFormatter_Settings();

