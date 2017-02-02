<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_EM_Template_SettingsProcessorHooks {

	function __construct() {

		add_filter(
			'Wassup_Template_SettingsProcessor:page_blocks',
			array($this, 'get_page_blocks'),
			10,
			3
		);
	}

	function get_page_blocks($ret, $hierarchy, $include_common) {

		// Page or Blocks inserted in Home
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

			$pageblocks_map = array(
				POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCK_ALLUSERS_SCROLLMAP,
				POP_WPAPI_PAGE_SEARCHUSERS => GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;
			}
		}

		// Author page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {

			$pageblocks_map = array(
				POP_COREPROCESSORS_PAGE_FOLLOWERS  => GD_TEMPLATE_BLOCK_AUTHORFOLLOWERS_SCROLLMAP,
				POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS  => GD_TEMPLATE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;
			}
		}

		// Single page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_SINGLE) {

			$pageblocks_map = array(
				POP_COREPROCESSORS_PAGE_POSTAUTHORS => GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLLMAP,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDBY => GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP,
				POP_COREPROCESSORS_PAGE_UPVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP,
				POP_COREPROCESSORS_PAGE_DOWNVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;
			}
		}

		// Tag page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_TAG) {

			$pageblocks_map = array(
				POP_COREPROCESSORS_PAGE_SUBSCRIBERS => GD_TEMPLATE_BLOCK_TAGSUBSCRIBERS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;
			}
		}

		return $ret;	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_EM_Template_SettingsProcessorHooks();
