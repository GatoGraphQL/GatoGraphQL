<?php
namespace PoP\CMSModel;

define ('GD_DATALOADER_CONVERTIBLEUSERLIST', 'convertible-user-list');

class Dataloader_ConvertibleUserList extends Dataloader_UserListBase {

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
new Dataloader_ConvertibleUserList();