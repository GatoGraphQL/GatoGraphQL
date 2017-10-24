<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Typeahead Formatting
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_DATASTRUCTURE_RESULTS', 'results');

class GD_DataLoad_DataStructureFormatter_Results extends GD_DataLoad_DataStructureFormatter {

	function get_name() {
	
		return GD_DATALOAD_DATASTRUCTURE_RESULTS;
	}
	
	// Just return the first content of the DB (disregard dbKey)
	function get_formatted_data($settings, $runtimesettings, $sitemapping, $data) {
	
		$dbcontent = array_values($data['database']);

		if ($dbcontent && $dbcontent[0]) {
			// $json = json_encode($dbcontent[0]);
			$json = $dbcontent[0];
		}
		else {
			// $json = json_encode(array(), JSON_FORCE_OBJECT);
			$json = array();
		}
		return array('json' => $json);
	}

	// Add dataitem to dataset
	function add_to_dataset(&$dataset, $id, $data_fields, $resultitem, $fieldprocessor) {
	
		$dataitem = $this->get_dataitem($data_fields, $resultitem, $fieldprocessor);
		
		$dataset[] = $dataitem;	

		return $dataitem;
	}

}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_DataStructureFormatter_Results();

