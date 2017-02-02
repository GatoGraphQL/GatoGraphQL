<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoader_Tag extends GD_DataLoader {

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_TAG;
	}
	
	function get_dataquery() {

		return GD_DATAQUERY_TAG;
	}
	
	/**
     * Function to override
     */
	function execute_get_data($ids) {
	
		if ($ids) {

			$query = array(
				'include' => implode(', ', $ids)
			);
			return get_tags($query);
		}
		
		return null;
	}

	function get_execution_priority() {

		return 1;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_TAGS;
	}
}