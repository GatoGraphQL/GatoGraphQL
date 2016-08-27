<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Profiles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_FilterComponent_Communities_Post extends GD_URE_FilterComponent_CommunityAuthor_Post {
	
	function get_filterformcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_POST;
	}

	function get_formcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITIES_POST;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_ure_filtercomponent_communities_post;
$gd_ure_filtercomponent_communities_post = new GD_URE_FilterComponent_Communities_Post();