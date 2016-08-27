<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_NOUSERS', 'nousers');
 
class GD_DataLoader_NoUsers extends GD_DataLoader_Static {

	function get_name() {
    
		return GD_DATALOADER_NOUSERS;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_USERS;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_NoUsers();