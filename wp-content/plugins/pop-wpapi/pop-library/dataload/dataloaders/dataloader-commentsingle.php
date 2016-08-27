<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_DATALOADER_COMMENTSINGLE', 'comment-single');

class GD_DataLoader_CommentSingle extends GD_DataLoader {

	function get_name() {
    
		return GD_DATALOADER_COMMENTSINGLE;
	}

	function get_dataquery() {

		return GD_DATAQUERY_COMMENT;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {

		// Return the ID contained in "include"
		return array($vars['include']);
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_COMMENT;
	}
	
	/**
     * Function to override
     */
	function execute_get_data($ids) {

		return array(get_comment($ids[0]));
	}
	
	function get_execution_priority() {

		return 2;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_COMMENTS;
	}

}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_CommentSingle();