<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_DATALOADER_TAG', 'tag');

// We are assuming we are in either page or single templates
class GD_DataLoader_TagSingle extends GD_DataLoader {

	function get_name() {
    
		return GD_DATALOADER_TAG;
	}

	function get_dataquery() {

		return GD_DATAQUERY_TAG;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {
	
		return array(get_queried_object_id());
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_TAG;
	}
	
	/**
     * Function to override
     */
	function execute_get_data($ids) {
	
		return array(get_queried_object());
	}
	
	function get_execution_priority() {

		return 1;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_TAGS;
	}

}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_TagSingle();