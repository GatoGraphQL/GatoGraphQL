<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_NONOTIFICATIONS', 'nonotifications');
 
class GD_DataLoader_NoNotifications extends GD_DataLoader_Static {

	function get_name() {
    
		return GD_DATALOADER_NONOTIFICATIONS;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_NOTIFICATIONS;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_NoNotifications();