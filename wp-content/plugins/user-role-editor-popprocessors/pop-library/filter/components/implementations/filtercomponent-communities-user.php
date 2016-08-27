<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter OrganizationTypes
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_FilterComponent_Communities_User extends GD_FilterComponent_Metaquery_User {
	
	function get_filterformcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_USER;
	}

	function get_formcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITIES_USER;
	}

	function get_filterformcomponent_value() {
	
		$selected_communities = parent::get_filterformcomponent_value();
				
		// Add the 'contributecontent' status to the value for each selected community
		return array_map('gd_ure_get_community_metavalue_contributecontent', $selected_communities);
	}

	function get_meta_key() {
	
		return GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_ure_filtercomponent_communities_user;
$gd_ure_filtercomponent_communities_user = new GD_URE_FilterComponent_Communities_User();