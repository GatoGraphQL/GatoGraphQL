<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Ajax Load Users Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_FIXEDUSERLIST', 'fixeduser-list');

/**
 * Similar to GD_DataLoader_UserList, but the items to retrieve back are a fixed list, no query involved (useful for Who we are page, to bring the Team users)
 */
class GD_DataLoader_FixedUserList extends GD_DataLoader_UserList {

    function get_name() {
    
		return GD_DATALOADER_FIXEDUSERLIST;
	}

	function get_query($vars = array()) {
	
		$query = parent::get_query($vars);

		$query['limit'] = 0;

		return $query;
	}

	function execute_query_ids($query) {
    
		// No need to execute the query, only return the ids under 'include'
		if ($include = $query['include']) {
			return $include;
		}

		// Make sure it returns no results
		return array();
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_FixedUserList();