<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_NOPOSTS', 'noposts');
 
class GD_DataLoader_NoPosts extends GD_DataLoader_Static {

	function get_name() {
    
		return GD_DATALOADER_NOPOSTS;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_POSTS;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_NoPosts();