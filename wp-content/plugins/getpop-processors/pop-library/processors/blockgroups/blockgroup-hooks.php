<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GetPoP_Processors_BlockGroupHooks {

	function __construct() {

		// Change the Blockgroups to show on the Homepage and Author page
		add_filter(
			'GD_Template_Processor_MainBlockGroups:blockgroups:home_tops',
			array($this, 'home_topblockgroups')
		);

		// Do not add the Trending Hashtags or the Events Calendar to the Sideinfo
		add_filter(
			'PoPTheme_Wassup_BlockGroupHooks:sidebar_blockgroup_blocks:include_screengroups',
			array($this, 'include_screengroups')
		);
		add_filter(
			'PoPTheme_Wassup_EM_BlockGroupHooks:sidebar_blockgroup_blocks:include_screengroups',
			array($this, 'include_screengroups')
		);
		add_filter(
			'GD_Template_Processor_SidebarBlockGroupsBase:blocks', 
			array($this, 'get_sidebar_blockgroup_blocks'),
			0,
			4
		);

		// Show only the Sections menu in the Blockgroup Side
		add_filter(
			'GD_Template_Processor_SideBlockGroups:blocks',
			array($this, 'get_blockgroupside_blocks'),
			10,
			2
		);
	}

	function get_blockgroupside_blocks($blocks, $template_id) {

		if ($template_id == GD_TEMPLATE_BLOCKGROUP_SIDE) {

			return array(
				GD_TEMPLATE_BLOCK_MENU_SIDE_SECTIONS,
			);
		}

		return $blocks;
	}
	function home_topblockgroups($blockgroups) {

		return array(
			GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG,
			GD_GETPOP_TEMPLATE_BLOCKGROUP_HOME
		);
		// return GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME;
	}

	function include_screengroups($screengroups) {

		return array();
	}

	function get_sidebar_blockgroup_blocks($blocks, $screengroup, $screen, $template_id) {

		// Add the Newsletter to all BlockGroups in the Sideinfo
		$blocks[] = GD_TEMPLATE_BLOCK_NEWSLETTER;
		return $blocks;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_Processors_BlockGroupHooks();
