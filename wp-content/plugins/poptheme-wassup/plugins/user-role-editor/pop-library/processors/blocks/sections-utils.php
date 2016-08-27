<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/


class PoPTheme_Wassup_URE_Template_Processor_SectionBlocksUtils {

	public static function add_dataloadqueryargs_communitymembers(&$ret, $community_id) {

		if (!isset($ret['meta-query'])) { $ret['meta-query'] = array(); }
		
		// It must fulfil 2 conditions: the user must've said he/she's a member of this organization,
		// And the Organization must've accepted it by leaving the Show As Member privilege on
		$ret['meta-query'][] = array(
			'key' => GD_MetaManager::get_meta_key(GD_URE_METAKEY_PROFILE_COMMUNITIES, GD_META_TYPE_USER),
			'value' => $community_id,
			'compare' => 'IN'
		);
		$ret['meta-query'][] = array(
			'key' => GD_MetaManager::get_meta_key(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS, GD_META_TYPE_USER),
			'value' => gd_ure_get_community_metavalue($community_id, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE),
			'compare' => 'IN'
		);
	}

	public static function get_community_members($community_id) {

		// Build the query args from the Utils
		$queryargs = array();
		self::add_dataloadqueryargs_communitymembers($queryargs, $community_id);

		$query = array(
			'fields' => 'ID',
			'number' => '', // Bring all the results
			'meta_query' => $queryargs['meta-query'],
		);

		return get_users($query);
	}
}