<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/


class PoPCore_Template_Processor_SectionBlocksUtils {

	public static function add_dataloadqueryargs_latestcounts(&$ret) {

		$ret['tax-query'] = array_merge(
			array(
				'relation' => 'OR'
			),
			gd_dataload_latestcounts_taxquery_items()
		);
		$ret['post-type'] = gd_dataload_posttypes();
	}

	public static function add_dataloadqueryargs_allcontent(&$ret) {

		$conf_allcontent_taxquery = array_merge(
			array(
				'relation' => 'OR'
			),
			gd_dataload_allcontent_taxquery_items()
		);
		$ret['tax-query'] = $conf_allcontent_taxquery;
		$ret['post-type'] = gd_dataload_posttypes();
	}

	public static function add_dataloadqueryargs_references(&$ret, $post_id = null) {

		if (!$post_id) {
			$vars = GD_TemplateManager_Utils::get_vars();
			$post = $vars['global-state']['post']/*global $post*/;
			$post_id = $post->ID;
		}

		// Set it for 'All Content' (eg: exclude Highlights, which also use references but are a special case)
		// PoPCore_Template_Processor_SectionBlocksUtils::add_dataloadqueryargs_allcontent($ret);
		self::add_dataloadqueryargs_allcontent($ret);

		// Find all related posts
		$meta_query = array(
			'key' => GD_MetaManager::get_meta_key(GD_METAKEY_POST_REFERENCES),
			'value' => array($post_id),
			'compare' => 'IN'
		);
		if (!isset($ret['meta-query'])) { $ret['meta-query'] = array(); }
		$ret['meta-query'][] = $meta_query;
	}

	public static function get_referencedby($post_id) {

		// Build the query args from the Utils
		$queryargs = array();
		self::add_dataloadqueryargs_references($queryargs, $post_id);

		$query = array(
			'fields' => 'ids',
			'posts_per_page' => -1, // Bring all the results
			'meta_query' => $queryargs['meta-query'],
			'post_type' => $queryargs['post-type'],
			'tax_query' => $queryargs['tax-query'],
		);

		return get_posts($query);
	}

	public static function add_dataloadqueryargs_allcontent_bysingletag(&$ret) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$tag_id = $vars['global-state']['queried-object-id']/*get_queried_object_id()*/;

		// Must create a nested taxonomy (https://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters),
		// where in each 'AND' item we query for the post category/event and the tag
		$conf_allcontent_taxquery = array(
			'relation' => 'OR',
		);
		$tax_query_items = gd_dataload_allcontent_taxquery_items();
		foreach ($tax_query_items as $tax_query_item) {
			$item = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'post_tag',
					'terms' => array($tag_id),
				)
			);
			$item[] = $tax_query_item;
			$conf_allcontent_taxquery[] = $item;
		}
		$ret['tax-query'] = $conf_allcontent_taxquery;
		$ret['post-type'] = gd_dataload_posttypes();
	}
}