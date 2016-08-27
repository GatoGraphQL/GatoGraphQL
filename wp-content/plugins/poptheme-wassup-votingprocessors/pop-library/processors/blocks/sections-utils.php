<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/


class VotingProcessors_Template_Processor_CustomSectionBlocksUtils {

	public static function add_dataloadqueryargs_opinionatedvotereferences(&$ret, $post_id = null) {

		// If $post_id is null, then we are retrieving the general OpinionatedVoted, set through the homepage
		// It doesn't reference any article, so search for all OpinionatedVoteds without such a reference
		if ($post_id) {

			$meta_query = array(
				'key' => GD_MetaManager::get_meta_key(GD_METAKEY_POST_REFERENCES),
				'value' => array($post_id),
				'compare' => 'IN'
			);
		}
		else {

			$meta_query = array(
				'key' => GD_MetaManager::get_meta_key(GD_METAKEY_POST_REFERENCES),
				'compare' => 'NOT EXISTS'
			);
		}
		if (!isset($ret['meta_query'])) { $ret['meta_query'] = array(); }
		$ret['meta_query'][] = $meta_query;
		$ret['cat'] = POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES;
		$ret['fields'] = 'ids';
		// $ret['post_status'] = array('publish', 'draft');
	}

	public static function add_dataloadqueryargs_articleopinionatedvotereferences(&$ret) {

		// All results where there is an article involved
		$meta_query = array(
			'key' => GD_MetaManager::get_meta_key(GD_METAKEY_POST_REFERENCES),
			'compare' => 'EXISTS'
		);

		if (!isset($ret['meta_query'])) { $ret['meta_query'] = array(); }
		$ret['meta_query'][] = $meta_query;
		$ret['cat'] = POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES;
		$ret['fields'] = 'ids';
	}

	public static function add_dataloadqueryargs_singleopinionatedvotes(&$ret, $post_id = null) {

		if (!$post_id) {
			global $post;
			$post_id = $post->ID;
		}

		$ret['cat'] = POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES;

		// Find all related posts
		$meta_query = array(
			'key' => GD_MetaManager::get_meta_key(GD_METAKEY_POST_REFERENCES),
			'value' => array($post_id),
			'compare' => 'IN'
		);
		if (!isset($ret['meta-query'])) { $ret['meta-query'] = array(); }
		$ret['meta-query'][] = $meta_query;
	}
}