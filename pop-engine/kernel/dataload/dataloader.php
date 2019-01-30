<?php
namespace PoP\Engine;
 
abstract class Dataloader {

    function __construct() {
    
		$dataloader_manager = Dataloader_Manager_Factory::get_instance();
		$dataloader_manager->add($this);
	}
    
    abstract function get_name();

    /**
     * Function to override
     */
    function get_database_key() {

		return '';
	}

	/**
     * Function to override
     */
	function get_fieldprocessor() {

		return null;
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

	final function get_dataitems($formatter, $resultset, $ids_data_fields = array()) {	
		
		$fieldprocessor_manager = FieldProcessor_Manager_Factory::get_instance();

		if ($fieldprocessor_name = $this->get_fieldprocessor()) {

			$fieldprocessor = $fieldprocessor_manager->get($fieldprocessor_name);
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
	final function get_data($ids_data_fields = array()) {
	
		// Get the ids
		$ids = array_keys($ids_data_fields);

		// Execute the query, get data to iterate
		return $this->execute_get_data($ids);
	}	
}
	
