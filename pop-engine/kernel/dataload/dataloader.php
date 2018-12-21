<?php
namespace PoP\Engine;
 
abstract class Dataloader {

    function __construct() {
    
		global $gd_dataload_manager;
		$gd_dataload_manager->add($this);
	}
    
    /**
     * Function to override
     */
    function get_name() {
    
		return '';
	}
	
	/**
     * Function to override
     */
    function get_dataquery() {

		return null;
	}
	

	/**
     * Function to override
     */
	function execute_get_data($ids) {
	
		return array();
	}

	function get_dataitems($formatter, $resultset, $ids_data_fields = array()) {	
		
		global $gd_dataload_fieldprocessor_manager;

		if ($fieldprocessor_name = $this->get_fieldprocessor()) {

			$fieldprocessor = $gd_dataload_fieldprocessor_manager->get($fieldprocessor_name);
		}

		// Iterate data, extract into final results
		$databaseitems = $dbobjectids = array();
		if (!empty($resultset)) {
			foreach($resultset as $resultitem) {	

				// Obtain the data-fields for that $id
				$id = $fieldprocessor->get_id($resultitem);
				$data_fields = array(
					'primary' => $ids_data_fields[$id] ?? array(),
				);
				$dbobjectids[] = $id;	

				do_action(
					'Dataloader:modify_data_fields', 
					array(&$data_fields),
					$this
				);
				
				// Add to the dataitems
				foreach ($data_fields as $dbname => $db_data_fields) {

					if ($db_data_fields) {
						$databaseitems[$dbname] = $databaseitems[$dbname] ?? array();
						$dataitem = $formatter->add_to_dataitems($databaseitems[$dbname], $id, $db_data_fields, $resultitem, $fieldprocessor);
					}
				}
			}
		}
		
		return array(
			'dbobjectids' => $dbobjectids,
			'dbitems' => $databaseitems,
		);
	}
	

	/**
	 * key: id
	 * value: data-fields to fetch for that id
	 */
	function get_data($ids_data_fields = array()) {
	
		// Get the ids
		$ids = array_keys($ids_data_fields);

		// Execute the query, get data to iterate
		return $this->execute_get_data($ids);
	}	
	
	/**
     * Function to override
     */
	function get_fieldprocessor() {

		return null;
	}
 //    function get_fieldprocessor() {

	// 	global $gd_dataloader_fieldprocessor_manager, $gd_dataload_fieldprocessor_manager;
		
	// 	// From the dataloader name, obtain the FieldProcessor Name
	// 	$fieldprocessor_name = $gd_dataloader_fieldprocessor_manager->get_fieldprocessor($this->get_name());

	// 	// And from that, obtain the FieldProcessor
	// 	return $gd_dataload_fieldprocessor_manager->get($fieldprocessor_name);
	// }
	
	/**
     * Function to override
     */
	function get_database_key() {
	
		return '';
	}

}
	
