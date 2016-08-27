<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoader_Post extends GD_DataLoader {

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_POST;
	}
	
	function get_dataquery() {

		return GD_DATAQUERY_POST;
	}
	
	/**
     * Function to override
     */
	function execute_get_data($ids) {
	
		if ($ids) {

			$query = array(
				'include' => $ids,
				'post_type' => gd_dataload_posttypes()
			);
			return get_posts($query);
		}
		
		return null;
	}

	function get_execution_priority() {

		return 1;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_POSTS;
	}
}