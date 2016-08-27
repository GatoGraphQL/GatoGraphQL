<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Users Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_EDITLOCATION', 'editlocation');
 
// We are assuming we are in either page or single templates
class GD_DataLoader_EditLocation extends GD_DataLoader_EditPost {

	function get_name() {
    
		return GD_DATALOADER_EDITLOCATION;
	}

	function get_data_ids($vars = array(), $is_main_query = false) {

		// When editing a post in the frontend, set param "pid"
		if ($lid = $_REQUEST['lid']) {

			if (is_array($lid)) {

				return $lid;
			}
			return array($lid);
		}
		return array();
	}

	function get_post($post_id) {

		return em_get_location($post_id, 'post_id');
	}
	
	function get_database_key() {
	
		return GD_DATABASE_KEY_LOCATIONS;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_EditLocation();