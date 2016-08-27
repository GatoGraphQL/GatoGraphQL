<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_DATALOADER_SINGLE', 'single');

// We are assuming we are in either page or single templates
class GD_DataLoader_Single extends GD_DataLoader {

	function get_name() {
    
		return GD_DATALOADER_SINGLE;
	}

	function get_dataquery() {

		return GD_DATAQUERY_POST;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {
	
		// Simply return the global $post ID. 
		global $post;
		return array($post->ID);
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_POST;
	}
	
	/**
     * Function to override
     */
	function execute_get_data($ids) {
	
		global $post;
		return array($post);
	}
	
	function get_execution_priority() {

		return 1;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_POSTS;
	}

}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_Single();