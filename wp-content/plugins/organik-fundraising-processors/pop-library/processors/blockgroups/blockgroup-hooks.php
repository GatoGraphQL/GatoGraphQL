<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class OrganikFundraising_Processors_BlockGroupHooks {

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
			GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOME,
		);
	}

	function include_screengroups($screengroups) {

		return array();
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OrganikFundraising_Processors_BlockGroupHooks();
