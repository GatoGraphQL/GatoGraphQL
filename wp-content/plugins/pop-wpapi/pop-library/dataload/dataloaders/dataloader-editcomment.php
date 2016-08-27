<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_EDITCOMMENT', 'editcomment');
 
// We are assuming we are in either page or single templates
class GD_DataLoader_EditComment extends GD_DataLoader {

	function get_name() {
    
		return GD_DATALOADER_EDITCOMMENT;
	}

	function get_dataquery() {

		return GD_DATAQUERY_COMMENT;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {
	
		// When editing a post in the frontend, set param "pid"
		if ($cid = $_REQUEST['cid']) {
			
			if (is_array($cid)) {

				$cid = GD_TemplateManager_Utils::limit_results($cid);
				return $cid;
			}
			return array($cid);
		}
		return array();
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_COMMENT;
	}
	
	/**
     * Function to override
     */
	function execute_get_data($ids) {
	
		if ($comment_id = $ids[0]) {
			return array(get_comment($comment_id));
		}
		return null;
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
new GD_DataLoader_EditComment();