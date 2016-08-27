<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_VotingProcessors_BlockGroupHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomSectionBlocksUtils:get_single_submenu:skip_categories',
			array($this, 'skip_categories')
		);
		add_filter(
			'GD_Template_Processor_MainBlockGroups:get_controlgroup_bottom:skip_categories',
			array($this, 'skip_categories')
		);
		add_filter(
			'GD_Template_Processor_MainBlockGroups:blocks:single',
			array($this, 'single_blocks')
		);
	}

	function single_blocks($blocks) {

		// Only for Links/WebPosts/Stories/Discussions/Announcements/Events
		$post_type = get_post_type();
		$add = (
			$post_type == 'post' && in_array(
				gd_get_the_main_category(), 
				array(
					// POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
					POPTHEME_WASSUP_CAT_WEBPOSTS,
					POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES,
					POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS,
					POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS,
				)
			)
		) || ($post_type == EM_POST_TYPE_EVENT);
		if ($add) {

			// Add the "What do you think about TPP" Create Block
			array_splice($blocks, array_search(GD_TEMPLATE_BLOCK_SINGLE_CONTENT, $blocks)+1, 0, array(GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE));
		}
		return $blocks;
	}

	function skip_categories($categories) {

		return array_merge(
			$categories,
			array(
				POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES,
			)
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_VotingProcessors_BlockGroupHooks();
