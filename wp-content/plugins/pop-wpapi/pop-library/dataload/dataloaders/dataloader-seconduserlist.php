<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Ajax Load Posts Library
 * This Class is needed to fetch posts after using GD_DATALOADER_POSTLIST
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_SECONDUSERLIST', 'seconduser-list');

class GD_DataLoader_SecondUserList extends GD_DataLoader_UserList {

	function get_name() {
    
		return GD_DATALOADER_SECONDUSERLIST;
	}

    function get_execution_priority() {
    
		return 4;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_SecondUserList();