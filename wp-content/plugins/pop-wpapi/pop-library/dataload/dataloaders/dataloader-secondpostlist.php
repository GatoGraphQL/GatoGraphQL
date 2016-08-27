<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Ajax Load Posts Library
 * This Class is needed to fetch posts after using GD_DATALOADER_POSTLIST
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_SECONDPOSTLIST', 'secondpost-list');

class GD_DataLoader_SecondPostList extends GD_DataLoader_PostList {

	function get_name() {
    
		return GD_DATALOADER_SECONDPOSTLIST;
	}

    function get_execution_priority() {
    
		return 2;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_SecondPostList();