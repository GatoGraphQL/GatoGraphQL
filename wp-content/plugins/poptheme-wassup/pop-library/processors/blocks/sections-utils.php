<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/


class GD_Template_Processor_CustomSectionBlocksUtils {

	public static function get_author_title() {

		global $author;
		$ret = get_the_author_meta('display_name', $author);

		if ($page_id = GD_TemplateManager_Utils::get_hierarchy_page_id()) {

			$ret = sprintf(
				'<small>%s <i class="fa fa-fw fa-angle-double-right"></i></small> %s',
				$ret,
				get_the_title($page_id)
			);
		}
		return $ret;
	}

	public static function get_single_title() {

		global $post;
		$ret = get_the_title($post->ID);

		if ($page_id = GD_TemplateManager_Utils::get_hierarchy_page_id()) {

			$ret = sprintf(
				'<small>%s <i class="fa fa-fw fa-angle-double-right"></i></small> %s',
				$ret,
				get_the_title($page_id)
			);
		}

		return $ret;
	}

	public static function get_single_submenu() {

		global $post;
		if (get_post_status($post->ID) == 'publish')  {

			// Comment Leo 09/11/2015: No need to add this information for the Upvote/Downvote, it's too much
			// No ned for the highlights
			$cat = gd_get_the_main_category();
			$skip = apply_filters('GD_Template_Processor_CustomSectionBlocksUtils:get_single_submenu:skip_categories', array());
			if (!in_array($cat, $skip)) {

				return GD_TEMPLATE_SUBMENU_SINGLE;
			}
		}
		
		return null;
	}

	public static function get_author_dataloadsource($template_id) {

		// These are the Profile Blocks, they will always be used inside an is_author() page
		// Then, point them not the is_page() page, but to the author url (mesym.com/u/mesym) and
		// an attr "tab" indicating this page through its path. This way, users can go straight to their 
		// information by typing their url: mesym.com/u/mesym?tab=events. Also good for future API

		global $gd_template_settingsmanager, $author;
		$url = get_author_posts_url($author);
		$page_id = $gd_template_settingsmanager->get_block_page($template_id, GD_SETTINGS_HIERARCHY_AUTHOR);
		$ret = GD_TemplateManager_Utils::add_tab($url, $page_id);
		
		// Allow URE to add the Organization/Community content source attribute
		$ret = apply_filters('GD_Template_Processor_CustomSectionBlocks:get_dataload_source:author', $ret, $author);

		return $ret;
	}

	public static function get_single_dataloadsource($template_id) {

		// Similar for single pages, the url will be /announcements/we-re-launching-again/?tab=content
		global $gd_template_settingsmanager, $post;
		$url = get_permalink($post->ID);
		$page_id = $gd_template_settingsmanager->get_block_page($template_id, GD_SETTINGS_HIERARCHY_SINGLE);
		$ret = GD_TemplateManager_Utils::add_tab($url, $page_id);

		return $ret;
	}

	public static function add_dataloadqueryargs_authorcontent(&$ret) {

		global $author;

		// Allow to override with User Role Editor: for organizations: Find all the members of this community, and filter all posts accordingly
		// Only filter if the 'author' attribute has not been set yet. If it has been set, it must've been done by the filter, 
		// which will allow only members belonging to the community. So use that one instead
		// if (!$ret['author']) {
		$authors = apply_filters('gd_template:dataload_query_args:authors', array($author));
		$ret['author'] = implode(',', $authors);
		// }
	}

	public static function add_dataloadqueryargs_authorfollowers(&$ret) {

		global $author;
		if (!isset($ret['meta-query'])) { $ret['meta-query'] = array(); }
		
		// It must fulfil 2 conditions: the user must've said he/she's a member of this organization,
		// And the Organization must've accepted it by leaving the Show As Member privilege on
		$ret['meta-query'][] = array(
			'key' => GD_MetaManager::get_meta_key(GD_METAKEY_PROFILE_FOLLOWSUSERS, GD_META_TYPE_USER),
			'value' => $author,
			'compare' => 'IN'
		);
	}

	public static function add_dataloadqueryargs_authorfollowingusers(&$ret) {

		global $author;
		if (!isset($ret['meta-query'])) { $ret['meta-query'] = array(); }
		
		// It must fulfil 2 conditions: the user must've said he/she's a member of this organization,
		// And the Organization must've accepted it by leaving the Show As Member privilege on
		$ret['meta-query'][] = array(
			'key' => GD_MetaManager::get_meta_key(GD_METAKEY_PROFILE_FOLLOWEDBY, GD_META_TYPE_USER),
			'value' => $author,
			'compare' => 'IN'
		);
	}

	public static function add_dataloadqueryargs_authorrecommendedposts(&$ret) {

		global $author;
		// Find all recommended posts by this author
		$meta_query = array(
			'key' => GD_MetaManager::get_meta_key(GD_METAKEY_POST_RECOMMENDEDBY),
			'value' => array($author),
			'compare' => 'IN'
		);
		if (!isset($ret['meta-query'])) { $ret['meta-query'] = array(); }
		$ret['meta-query'][] = $meta_query;
		$ret['post-type'] = gd_dataload_posttypes(); // Allow also Events post types, so these can be fetched from Stories (field references)
	}

	public static function add_dataloadqueryargs_singlehighlights(&$ret, $post_id = null) {

		if (!$post_id) {
			global $post;
			$post_id = $post->ID;
		}

		$ret['cat'] = POPTHEME_WASSUP_CAT_HIGHLIGHTS;

		// Find all related posts
		$meta_query = array(
			'key' => GD_MetaManager::get_meta_key(GD_METAKEY_POST_REFERENCES),
			'value' => array($post_id),
			'compare' => 'IN'
		);
		if (!isset($ret['meta-query'])) { $ret['meta-query'] = array(); }
		$ret['meta-query'][] = $meta_query;
	}

	public static function add_dataloadqueryargs_singleauthors(&$ret) {

		// Include only the authors of the current post
		$ret['include'] = gd_get_postauthors();
	}

	public static function add_dataloadqueryargs_recommendedby(&$ret) {

		global $post;

		// Find all related posts
		$meta_query = array(
			'key' => GD_MetaManager::get_meta_key(GD_METAKEY_PROFILE_RECOMMENDSPOSTS, GD_META_TYPE_USER),
			'value' => array($post->ID),
			'compare' => 'IN'
		);
		if (!isset($ret['meta-query'])) { $ret['meta-query'] = array(); }
		$ret['meta-query'][] = $meta_query;
	}

	public static function add_dataloadqueryargs_upvotedby(&$ret) {

		global $post;

		// Find all related posts
		$meta_query = array(
			'key' => GD_MetaManager::get_meta_key(GD_METAKEY_PROFILE_UPVOTESPOSTS, GD_META_TYPE_USER),
			'value' => array($post->ID),
			'compare' => 'IN'
		);
		if (!isset($ret['meta-query'])) { $ret['meta-query'] = array(); }
		$ret['meta-query'][] = $meta_query;
	}

	public static function add_dataloadqueryargs_downvotedby(&$ret) {

		global $post;

		// Find all related posts
		$meta_query = array(
			'key' => GD_MetaManager::get_meta_key(GD_METAKEY_PROFILE_DOWNVOTESPOSTS, GD_META_TYPE_USER),
			'value' => array($post->ID),
			'compare' => 'IN'
		);
		if (!isset($ret['meta-query'])) { $ret['meta-query'] = array(); }
		$ret['meta-query'][] = $meta_query;
	}
}