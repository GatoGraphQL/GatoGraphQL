<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoader_User extends GD_DataLoader {

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_USER;
	}

	function get_dataquery() {

		return GD_DATAQUERY_USER;
	}
	
	/**
     * Function to override
     */
	function execute_get_data($ids) {
	
		if ($ids) {

			$ret = array();
			foreach ($ids as $user_id) {
				
				$ret[] = get_user_by('id', $user_id);
			}
			return $ret;
		}
		
		return null;
	}

	function get_execution_priority() {

		return 1;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_USERS;
	}
}