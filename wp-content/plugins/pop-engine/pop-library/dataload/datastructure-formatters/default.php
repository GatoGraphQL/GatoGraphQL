<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_DATALOAD_DATASTRUCTURE_DEFAULT', 'default');

class GD_DataLoad_DataStructureFormatter_Default extends GD_DataLoad_DataStructureFormatter {

	function get_name() {
			
		return GD_DATALOAD_DATASTRUCTURE_DEFAULT;
	}

	function get_formatted_settings($settings, $runtimesettings, $sitemapping) {
	
		// Combine Settings, Data and Feedback into 1 unique Structure, so that it can be delivered under 1 single JSON for the ajax load
		$json = array();
		if ($settings) {

			$json['sitemapping'] = $sitemapping;
			$json['runtimesettings'] = $runtimesettings;
			$json['settings'] = $settings;
		}

		return $json;
	}
	function get_formatted_data($data) {
	
		$json = array();
		if ($data) {

			$json['dataset'] = $data['dataset'];
			$json['database'] = $data['database'];
			$json['userdatabase'] = $data['userdatabase'];
			$json['query-state'] = $data['query-state'];
			$json['feedback'] = $data['feedback'];
		}

		return $json;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
$gd_dataload_formatter_default = new GD_DataLoad_DataStructureFormatter_Default();

// Set as the default one
global $gd_dataload_datastructureformat_manager;
$gd_dataload_datastructureformat_manager->set_default($gd_dataload_formatter_default);