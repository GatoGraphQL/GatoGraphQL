<?php
namespace PoP\CMSModel;

define ('GD_DATALOADER_USERLIST', 'user-list');

class Dataloader_UserList extends Dataloader_UserListBase {

	function get_name() {
    
		return GD_DATALOADER_USERLIST;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new Dataloader_UserList();