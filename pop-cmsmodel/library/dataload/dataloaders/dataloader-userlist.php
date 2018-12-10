<?php

define ('GD_DATALOADER_USERLIST', 'user-list');

class GD_Dataloader_UserList extends GD_Dataloader_UserListBase {

	function get_name() {
    
		return GD_DATALOADER_USERLIST;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_UserList();