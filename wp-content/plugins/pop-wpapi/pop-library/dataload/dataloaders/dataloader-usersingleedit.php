<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_USERSINGLEEDIT', 'editsingle-user');
 
// We are assuming we are in either page or single templates
class GD_DataLoader_UserSingleEdit extends GD_DataLoader_User {

	function get_name() {
    
		return GD_DATALOADER_USERSINGLEEDIT;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {
	
		$vars = GD_TemplateManager_Utils::get_vars();
		return array($vars['global-state']['current-user-id']/*get_current_user_id()*/);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_UserSingleEdit();