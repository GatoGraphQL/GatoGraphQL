<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_BlockGroupHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_SidebarBlockGroupsBase:blocks', 
			array($this, 'get_sidebar_blockgroup_blocks'),
			0,
			4
		);
		add_filter(
			'GD_Template_Processor_CustomSectionBlocksUtils:get_single_submenu:skip_categories',
			array($this, 'skip_categories')
		);
		add_filter(
			'GD_Template_Processor_MainBlockGroups:get_controlgroup_bottom:skip_categories',
			array($this, 'skip_categories')
		);
	}

	function skip_categories($categories) {

		return array_merge(
			$categories,
			array(
				POPTHEME_WASSUP_CAT_HIGHLIGHTS,
			)
		);
	}

	function get_sidebar_blockgroup_blocks($blocks, $screengroup, $screen, $template_id) {

		// Add the Trending Tags to all BlockGroups in the Sideinfo
		// Allow GetPoP to remove it
		$include_screengroups = apply_filters(
			'PoPTheme_Wassup_BlockGroupHooks:sidebar_blockgroup_blocks:include_screengroups',
			array(
				POP_SCREENGROUP_CONTENTREAD,
			)
		);
		$exclude_screens = array(
			POP_SCREEN_TAGS,
		);
		if (in_array($screengroup, $include_screengroups) && !in_array($screen, $exclude_screens)) {
			
			$blocks[] = GD_TEMPLATE_BLOCK_TRENDINGTAGS_SCROLL_LIST;
		}

		return $blocks;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_BlockGroupHooks();
