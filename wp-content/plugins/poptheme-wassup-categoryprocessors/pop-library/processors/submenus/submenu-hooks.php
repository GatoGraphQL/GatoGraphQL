<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_CategoryProcessors_SubmenuHooks {

	function __construct() {

		// Execute before the Events, so it can add the $current_blockgroup button right next to "All Content" button
		add_filter(
			'GD_Template_Processor_CustomSubMenus:author:blockgroupitems', 
			array($this, 'singleauthorsubmenu_blockunits'),
			0,
			2
		);
	}

	function singleauthorsubmenu_blockunits($blockunits, $current_blockgroup) {

		// Under Posts
		if ($add = array_values(PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cat_authorblockgroups())) {
			array_splice($blockunits[GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORWEBPOSTS], 0, 0, $add);

			if (in_array($current_blockgroup, $add)) {

				$blockunits[$current_blockgroup] = array();
			}
		}
		
		return $blockunits;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_CategoryProcessors_SubmenuHooks();
