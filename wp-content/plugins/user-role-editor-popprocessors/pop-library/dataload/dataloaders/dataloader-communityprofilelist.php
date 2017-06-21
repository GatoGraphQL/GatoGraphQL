<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Users Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// define ('GD_URE_DATALOADER_COMMUNITY_PROFILELIST', 'ure-community-profile-list');
define ('GD_URE_DATALOADER_COMMUNITY_USERLIST', 'ure-community-user-list');

// class GD_URE_DataLoader_CommunityProfileList extends GD_DataLoader_ProfileList {
class GD_URE_DataLoader_CommunityUserList extends GD_DataLoader_UserList {

	function get_name() {
    
		// return GD_URE_DATALOADER_COMMUNITY_PROFILELIST;
		return GD_URE_DATALOADER_COMMUNITY_USERLIST;
	}

	function execute_query_ids($query) {

		$ret = parent::execute_query_ids($query);

		// Also include the current author
		$vars = GD_TemplateManager_Utils::get_vars();
		$author = $vars['global-state']['author']/*global $author*/;
    	array_unshift($ret, $author);

    	return $ret;
	}

}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// new GD_URE_DataLoader_CommunityProfileList();
new GD_URE_DataLoader_CommunityUserList();