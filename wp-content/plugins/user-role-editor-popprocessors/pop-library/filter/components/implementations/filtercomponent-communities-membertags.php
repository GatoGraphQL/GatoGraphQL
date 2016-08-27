<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter ArticleCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_Communities_MemberTags extends GD_FilterComponent_Metaquery_User {

	function get_filterformcomponent_value() {
	
		$selected_tags = parent::get_filterformcomponent_value();
				
		// Add the current user_id <= the community
		return array_map('gd_ure_get_community_metavalue_currentcommunity', $selected_tags);
	}
		
	function get_filterformcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERTAGS;
	}
		
	function get_formcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERTAGS;
	}

	function get_meta_key() {
	
		return GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_ure_filtercomponent_communities_membertags;
$gd_ure_filtercomponent_communities_membertags = new GD_FilterComponent_Communities_MemberTags();