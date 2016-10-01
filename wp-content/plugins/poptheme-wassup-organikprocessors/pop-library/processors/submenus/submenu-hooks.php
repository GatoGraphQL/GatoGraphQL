<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_OrganikProcessors_SubmenuHooks {

	function __construct() {

		// Execute before the Events, so it can add the $current_blockgroup button right next to "All Content" button
		add_filter(
			'GD_Template_Processor_CustomSubMenus:author:blockgroupitems', 
			array($this, 'singleauthorsubmenu_blockunits'),
			0,
			2
		);
		add_filter(
			'GD_Template_Processor_CustomSubMenus:tag:blockgroupitems', 
			array($this, 'tagsubmenu_blockunits'),
			0,
			2
		);
	}

	function singleauthorsubmenu_blockunits($blockunits, $current_blockgroup) {

		// If the values for the constants were kept in false (eg: Farms not needed for TPP Debate) then don't add them
		if (POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS) {
			$blockunits[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFARMS;
		}
		
		return $blockunits;
	}
	function tagsubmenu_blockunits($blockunits, $current_blockgroup) {

		if (POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS) {
			$blockunits[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGFARMS;
		}
		
		return $blockunits;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_OrganikProcessors_SubmenuHooks();
