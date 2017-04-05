<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_StaticSearchUtils {

	public static function get_content_search_url($query_wildcard = GD_JSPLACEHOLDER_QUERY/*'%QUERY'*/) {

		global $gd_filter_manager, $gd_filtercomponent_search;

		$gd_filter_wildcardposts = $gd_filter_manager->get_filter(GD_FILTER_WILDCARDPOSTS);

		// Add static suggestions: Search Content and Search Users
		$searchcontent_url = get_permalink(POP_WPAPI_PAGE_SEARCHPOSTS);
		$filter_params = array(
			$gd_filtercomponent_search->get_name() => $query_wildcard
		);
		return $gd_filter_manager->add_filter_params($searchcontent_url, $gd_filter_wildcardposts, $filter_params);
	}

	public static function get_users_search_url($query_wildcard = GD_JSPLACEHOLDER_QUERY/*'%QUERY'*/) {

		global $gd_filter_manager, $gd_filtercomponent_name;

		$gd_filter_wildcardusers = $gd_filter_manager->get_filter(GD_FILTER_WILDCARDUSERS);

		$searchusers_url = get_permalink(POP_WPAPI_PAGE_SEARCHUSERS);
		$filter_params = array(
			$gd_filtercomponent_name->get_name() => $query_wildcard
		);
		return $gd_filter_manager->add_filter_params($searchusers_url, $gd_filter_wildcardusers, $filter_params);
	}

}