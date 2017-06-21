<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_USERSUBSCRIBESTOTAGS', 'user-subscribestotags');
 
class GD_DataLoader_UserSubscribesToTags extends GD_DataLoader_Tag {

	function get_name() {
    
		return GD_DATALOADER_USERSUBSCRIBESTOTAGS;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {
	
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {
			return array();
		}
		$ids = GD_MetaManager::get_user_meta($vars['global-state']['current-user-id']/*get_current_user_id()*/, GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS);

		return $ids ? $ids : array();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_UserSubscribesToTags();