<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Ajax Load Posts Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_FIXEDPOSTLIST', 'fixedpost-list');

/**
 * Similar to GD_DataLoader_PostList, but the items to retrieve back are from a fixed list
 */
class GD_DataLoader_FixedPostList extends GD_DataLoader_PostList {

    function get_name() {
    
		return GD_DATALOADER_FIXEDPOSTLIST;
	}

	function get_query($vars = array()) {
	
		$query = parent::get_query($vars);

		$query['limit'] = -1;

		return $query;
	}

	function execute_query_ids($query) {
    
		// No need to execute the query, only return the ids under 'include'
		return $query['include'];
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_FixedPostList();