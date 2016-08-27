<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_EM_BlockGroupHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_SidebarBlockGroupsBase:blocks', 
			array($this, 'get_sidebar_blockgroup_blocks'),
			10,
			4
		);
	}

	function get_sidebar_blockgroup_blocks($blocks, $screengroup, $screen, $template_id) {

		// Add the Events Calendar to all BlockGroups in the Sideinfo
		$include_screengroups = apply_filters(
			'PoPTheme_Wassup_EM_BlockGroupHooks:sidebar_blockgroup_blocks:include_screengroups',
			array(
				POP_SCREENGROUP_CONTENTREAD,
			)
		);
		$exclude_screens = array(
			// POP_SCREEN_AUTHORSECTIONCALENDAR,
			POP_SCREEN_SECTIONCALENDAR,
		);
		if (in_array($screengroup, $include_screengroups) && !in_array($screen, $exclude_screens)) {
			
			$blocks[] = GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR_ADDONS;
		}

		return $blocks;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_EM_BlockGroupHooks();
