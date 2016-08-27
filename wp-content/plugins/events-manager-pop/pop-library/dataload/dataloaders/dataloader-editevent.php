<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_EDITEVENT', 'editevent');
 
// We are assuming we are in either page or single templates
class GD_DataLoader_EditEvent extends GD_DataLoader_EditPost {

	function get_name() {
    
		return GD_DATALOADER_EDITEVENT;
	}

	function get_post($post_id) {
	
		return em_get_event($post_id, 'post_id');
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_EditEvent();