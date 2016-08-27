<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_EDITUSER', 'edituser');
 
class GD_DataLoader_EditUser extends GD_DataLoader_User {

	function get_name() {
    
		return GD_DATALOADER_EDITUSER;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {
	
		// When editing a post in the frontend, set param "uid"
		if ($uid = $_REQUEST['uid']) {
			
			if (is_array($uid)) {

				$uid = GD_TemplateManager_Utils::limit_results($uid);
				return $uid;
			}
			return array($uid);
		}
		return array();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_EditUser();