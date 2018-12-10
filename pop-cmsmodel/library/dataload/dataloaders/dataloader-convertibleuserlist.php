<?php
define ('GD_DATALOADER_CONVERTIBLEUSERLIST', 'convertible-user-list');

class GD_Dataloader_ConvertibleUserList extends GD_Dataloader_UserListBase {

	function get_name() {
    
		return GD_DATALOADER_CONVERTIBLEUSERLIST;
	}

    function get_fieldprocessor() {

		return GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_USERS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_ConvertibleUserList();