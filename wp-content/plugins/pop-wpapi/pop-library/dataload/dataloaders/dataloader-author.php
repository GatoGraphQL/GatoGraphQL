<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Users Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_AUTHOR', 'author');

class GD_DataLoader_Author extends GD_DataLoader {

	function get_name() {
    
		return GD_DATALOADER_AUTHOR;
	}

	function get_dataquery() {

		return GD_DATAQUERY_USER;
	}
	
	function get_execution_priority() {
    
		return 3;
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_USER;
	}

	function get_data_ids($vars = array(), $is_main_query = false) {
	
		global $author;
		return array($author);
	}
	
	function execute_get_data($ids) {
	
		global $author;
		return array(get_userdata($author));
	}
	
	function get_data_response($dataset, $atts) {
	
		// The only data we must give back is the author_id, which is the first element in the list. Extract it.
		$item = $dataset[0];

		return $item;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_USERS;
	}

}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_Author();