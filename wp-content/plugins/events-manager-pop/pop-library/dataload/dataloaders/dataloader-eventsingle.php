<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_DATALOADER_EVENTSINGLE', 'eventsingle');

// We are assuming we are in either page or single templates
class GD_DataLoader_EventSingle extends GD_DataLoader_Single {

	function get_name() {
    
		return GD_DATALOADER_EVENTSINGLE;
	}
	
	function get_database_key() {
	
		// return GD_DATABASE_KEY_EVENTS;
		return GD_DATABASE_KEY_POSTS;
	}

	function execute_get_data($ids) {
	
		return array(em_get_event($ids[0]));
	}

}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_EventSingle();