<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Base Typeahead
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_FilterComponent_CommunityAuthor_Post extends GD_FilterComponent {
	
	function get_author() {

		// Return all selected Communities + their Members
		$communities = $this->get_filterformcomponent_value();
		$members = array();
		// $key = GD_MetaManager::get_meta_key(GD_URE_METAKEY_PROFILE_COMMUNITIES, GD_META_TYPE_USER);
		$key = GD_MetaManager::get_meta_key(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES, GD_META_TYPE_USER);

		foreach ($communities as $community) {

			// Taken from https://codex.wordpress.org/Class_Reference/WP_Meta_Query
			$query = array(
				'fields' => 'ID',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => GD_MetaManager::get_meta_key(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS, GD_META_TYPE_USER),
						'value' => gd_ure_get_community_metavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE),
						'compare' => 'IN'
					),
					array(
						'key' => $key,
						'value' => gd_ure_get_community_metavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT),
						'compare' => 'IN'
					)
				)
			);

			$members = array_merge(
				$members,
				get_users($query)
			);
		}

		// Add the communities back, and make sure the results are unique
		return array_unique(
			array_merge(
				$communities,
				$members
			)
		);
	}	
	
}
