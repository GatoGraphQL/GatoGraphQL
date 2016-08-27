<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_USERRECOMMENDSPOSTS', 'user-recommendsposts');
 
class GD_DataLoader_UserRecommendsPosts extends GD_DataLoader_Post {

	function get_name() {
    
		return GD_DATALOADER_USERRECOMMENDSPOSTS;
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {
	
		return GD_MetaManager::get_user_meta(get_current_user_id(), GD_METAKEY_PROFILE_RECOMMENDSPOSTS);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_UserRecommendsPosts();