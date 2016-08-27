<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_USERUPVOTESPOSTS', 'user-upvotesposts');

class GD_DataLoader_UserUpvotesPosts extends GD_DataLoader_Post {

	function get_name() {

		return GD_DATALOADER_USERUPVOTESPOSTS;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {
	
		return GD_MetaManager::get_user_meta(get_current_user_id(), GD_METAKEY_PROFILE_UPVOTESPOSTS);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_UserUpvotesPosts();