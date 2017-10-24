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

	// Comment Leo 22/10/2017: instead of saving and operating with a String, work with the JSON object,
	// so no need to decode it for the AutomatedEmails and others
	// function get_formatted_data($settings, $runtimesettings, $sitemapping, $data) {
	
	// 	// Combine Settings, Data and Feedback into 1 unique Structure, so that it can be delivered under 1 single JSON for the ajax load
	// 	$json = array();
	// 	if ($data) {

	// 		// Settings and Data are put together in this funky way, because of the Settings cache:
	// 		// It already comes as json, while data is still an array
	// 		// So here combine it back into a json string
	// 		$encode = array(
	// 			'dataset' => $data['dataset'],
	// 			'database' => $data['database'],
	// 			'userdatabase' => $data['userdatabase'],
	// 			'query-state' => $data['query-state'],
	// 			'feedback' => $data['feedback'],
	// 		);

	// 		// Settings
	// 		if ($settings) {

	// 			// // sitemapping: no need to send 'template-extra-sources', since they are not used in the front-end for anything
	// 			// unset($sitemapping['template-extra-sources']);
	// 			$encode['sitemapping'] = $sitemapping;
	// 			$encode['runtimesettings'] = $runtimesettings;
	// 			$encode['settings'] = '{SETTINGS}';
	// 			$json = str_replace('"{SETTINGS}"', $settings, json_encode($encode));

	// 		}
	// 		// Data but not settings
	// 		else {

	// 			$json = json_encode($encode);
	// 		}
	// 	}
	// 	elseif ($settings) {

	// 		$encode = array(
	// 			'sitemapping' => $sitemapping,
	// 			'runtimesettings' => $runtimesettings,
	// 			'settings' => '{SETTINGS}'
	// 		);

	// 		$json = str_replace('"{SETTINGS}"', $settings, json_encode($encode));
	// 	}

	// 	$ret = array(
	// 		'json' => $json
	// 	);

	// 	// Add the crawlable data
	// 	$ret['crawlable-data'] = $data['crawlable-data'];

	// 	return $ret;
	// }
	function get_formatted_data($settings, $runtimesettings, $sitemapping, $data) {
	
		// Combine Settings, Data and Feedback into 1 unique Structure, so that it can be delivered under 1 single JSON for the ajax load
		$json = array();
		if ($data) {

			$json['dataset'] = $data['dataset'];
			$json['database'] = $data['database'];
			$json['userdatabase'] = $data['userdatabase'];
			$json['query-state'] = $data['query-state'];
			$json['feedback'] = $data['feedback'];
		}

		// Settings
		if ($settings) {

			$json['sitemapping'] = $sitemapping;
			$json['runtimesettings'] = $runtimesettings;
			$json['settings'] = $settings;
		}

		$ret = array(
			// 'json' => json_encode($encode),
			// 'encoded-json' => json_encode($encode),
			'json' => $json,
		);

		// Add the crawlable data
		$ret['crawlable-data'] = $data['crawlable-data'] ?? array();

		return $ret;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
$gd_dataload_formatter_default = new GD_DataLoad_DataStructureFormatter_Default();

// Set as the default one
global $gd_dataload_datastructureformat_manager;
$gd_dataload_datastructureformat_manager->set_default($gd_dataload_formatter_default);