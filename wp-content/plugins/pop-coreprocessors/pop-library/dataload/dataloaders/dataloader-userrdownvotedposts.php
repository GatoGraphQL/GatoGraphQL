<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_USERDOWNVOTESPOSTS', 'user-downvotesposts');

class GD_DataLoader_UserDownvotesPosts extends GD_DataLoader_Post {

	function get_name() {

		return GD_DATALOADER_USERDOWNVOTESPOSTS;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {

		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {
			return array();
		}
		$ids = GD_MetaManager::get_user_meta($vars['global-state']['current-user-id']/*get_current_user_id()*/, GD_METAKEY_PROFILE_DOWNVOTESPOSTS);

		return $ids ? $ids : array();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_UserDownvotesPosts();