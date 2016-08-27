<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_EDITPOST', 'editpost');
 
// We are assuming we are in either page or single templates
class GD_DataLoader_EditPost extends GD_DataLoader {

	function get_name() {
    
		return GD_DATALOADER_EDITPOST;
	}

	function get_dataquery() {

		return GD_DATAQUERY_POST;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {
	
		// When editing a post in the frontend, set param "pid"
		if ($pid = $_REQUEST['pid']) {

			if (is_array($pid)) {

				$pid = GD_TemplateManager_Utils::limit_results($pid);
				return $pid;
			}
			return array($pid);
		}
		return array();
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_POST;
	}
	
	/**
     * Function to override
     */
	function execute_get_data($ids) {
	
		$ret = array();
		foreach ($ids as $post_id) {

			$ret[] = $this->get_post($post_id);
		}
		return $ret;
	}

	function get_post($post_id) {
	
		return get_post($post_id);
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
new GD_DataLoader_EditPost();