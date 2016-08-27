<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter ArticleCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_Communities_MemberPrivileges extends GD_FilterComponent_Metaquery_User {

	function get_filterformcomponent_value() {
	
		$selected_privileges = parent::get_filterformcomponent_value();
				
		// Add the current user_id <= the community
		return array_map('gd_ure_get_community_metavalue_currentcommunity', $selected_privileges);
	}
		
	function get_filterformcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERPRIVILEGES;
	}
		
	function get_formcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERPRIVILEGES;
	}

	function get_meta_key() {
	
		return GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_ure_filtercomponent_communities_memberprivileges;
$gd_ure_filtercomponent_communities_memberprivileges = new GD_FilterComponent_Communities_MemberPrivileges();