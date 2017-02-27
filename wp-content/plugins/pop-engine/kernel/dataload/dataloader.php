<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_DATABASE_KEY_POSTS', 'posts');
define ('GD_DATABASE_KEY_PAGES', 'pages');
define ('GD_DATABASE_KEY_USERS', 'users');
define ('GD_DATABASE_KEY_TAGS', 'tags');
define ('GD_DATABASE_KEY_LOCATIONS', 'locations');
define ('GD_DATABASE_KEY_MENU', 'menu');
define ('GD_DATABASE_KEY_COMMENTS', 'comments');


class GD_DataLoader {

    function __construct() {
    
		global $gd_dataload_manager;
		$gd_dataload_manager->add($this->get_name(), $this->get_execution_priority(), $this);
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
    function get_execution_priority() {
    
		return 1;
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
	function get_data_ids($vars = array(), $is_main_query = false) {
	
		return array();
	}
	
	/**
     * Function to override
     */
	function execute_get_data($ids) {
	
		return array();
	}

	/**
     * Function to override
     */
	function get_crawlabledata_printer() {
	
		return null;
	}
	
	function get_dataset($formatter, $resultset, $ids_data_fields = array()) {	
		
		global $gd_dataquery_manager;

		$fieldprocessor = $this->get_fieldprocessor();
		if ($dataquery_name = $this->get_dataquery()) {
				
			$dataquery = $gd_dataquery_manager->get($dataquery_name);
		}

		// Get the crawlabledata Printer
		if ($crawlabledata_printer_name = $this->get_crawlabledata_printer()) {

			global $gd_dataloader_crawlabledataprinter_manager;
			
			// From the dataloader name, obtain the FieldProcessor Name
			$crawlabledata_printer = $gd_dataloader_crawlabledataprinter_manager->get_printer($crawlabledata_printer_name);
		}

		// Iterate data, extract into final results
		$dataset = $userdataset = array();
		$crawlable_data = array();
		if (!empty($resultset)) {
			foreach($resultset as $resultitem) {		

				// Obtain the data-fields for that $id
				$id = $fieldprocessor->get_id($resultitem);				
				$data_fields = isset($ids_data_fields[$id]) ? $ids_data_fields[$id] : array();
				$user_data_fields = array();

				// The data-fields include both state-less/cacheable and state-full/non-cacheable fields
				// The fieldprocessor knows what fields are non-cacheable. Get these and return the results in another array
				if ($dataquery) {
					$user_data_fields = array_intersect(
						$data_fields,
						$dataquery->get_loggedinuserfields()
					);
					$data_fields = array_diff($data_fields, $user_data_fields);
				}
				
				// Add to the dataset, and use it to add also to crawlable_data
				if ($data_fields) {
					
					$dataitem = $formatter->add_to_dataset($dataset, $id, $data_fields, $resultitem, $fieldprocessor);

					// Add all the dataitem values to be output for search engines
					// No need to add info belonging to the logged in user
					if ($crawlabledata_printer) {
						$crawlable_data[] = $crawlabledata_printer->get_crawlable_data($dataitem);
					}
				}

				// Add to the user-dataset all fields which depend on the user being logged in
				if ($user_data_fields) {
					$formatter->add_to_dataset($userdataset, $id, $user_data_fields, $resultitem, $fieldprocessor);
				}
			}
		}
		
		return array(
			'dataset' => $dataset,
			'user-dataset' => $userdataset,
			'crawlable-data' => $crawlable_data
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
	
	function get_data_response($dataset, $atts) {
	
		return $dataset;
	}

	function get_fieldprocessor() {

		global $gd_dataloader_fieldprocessor_manager, $gd_dataload_fieldprocessor_manager;
		
		// From the dataloader name, obtain the FieldProcessor Name
		$fieldprocessor_name = $gd_dataloader_fieldprocessor_manager->get_fieldprocessor($this->get_name());

		// And from that, obtain the FieldProcessor
		return $gd_dataload_fieldprocessor_manager->get($fieldprocessor_name);
	}
	
	/**
     * Function to override
     */
	function get_database_key() {
	
		return '';
	}

}
	
