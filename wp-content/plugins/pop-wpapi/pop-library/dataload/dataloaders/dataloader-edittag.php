<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_EDITTAG', 'edittag');
 
// We are assuming we are in either page or single templates
class GD_DataLoader_EditTag extends GD_DataLoader {

	function get_name() {
    
		return GD_DATALOADER_EDITTAG;
	}

	function get_dataquery() {

		return GD_DATAQUERY_TAG;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {
	
		// When editing a post in the frontend, set param "pid"
		if ($tid = $_REQUEST['tid']) {
			
			if (is_array($tid)) {

				$tid = GD_TemplateManager_Utils::limit_results($tid);
				return $tid;
			}
			
			return array($tid);
		}
		return array();
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_TAG;
	}
	
	/**
     * Function to override
     */
	function execute_get_data($ids) {
	
		if ($tag_id = $ids[0]) {
			return array(get_tag($tag_id));
		}
		return null;
	}

	function get_execution_priority() {

		return 2;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_TAGS;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_EditTag();