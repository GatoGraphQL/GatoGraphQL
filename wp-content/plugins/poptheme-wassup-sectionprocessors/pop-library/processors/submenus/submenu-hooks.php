<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_SectionProcessors_SubmenuHooks {

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

		// Under Posts
		$add = array();
		if (POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS) {
			$add[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORANNOUNCEMENTS;
		}
		if (POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS) {
			$add[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORDISCUSSIONS;
		}
		if (POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES) {
			$add[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORSTORIES;
		}
		if ($add) {
			array_splice($blockunits[GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORWEBPOSTS], 0, 0, $add);

			if (in_array($current_blockgroup, $add)) {

				$blockunits[$current_blockgroup] = array();
			}
		}

		// If the values for the constants were kept in false (eg: Projects not needed for TPP Debate) then don't add them
		if (POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS) {
			$blockunits[GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORLOCATIONPOSTS] = array();
		}
		
		return $blockunits;
	}

	function tagsubmenu_blockunits($blockunits, $current_blockgroup) {

		// Under Posts
		$add = array();
		if (POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS) {
			$add[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGANNOUNCEMENTS;
		}
		if (POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS) {
			$add[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGDISCUSSIONS;
		}
		if (POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES) {
			$add[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSTORIES;
		}
		if ($add) {
			array_splice($blockunits[GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGWEBPOSTS], 0, 0, $add);

			if (in_array($current_blockgroup, $add)) {

				$blockunits[$current_blockgroup] = array();
			}
		}

		// If the values for the constants were kept in false (eg: Projects not needed for TPP Debate) then don't add them
		if (POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS) {
			$blockunits[GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGLOCATIONPOSTS] = array();
		}
		
		return $blockunits;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_SectionProcessors_SubmenuHooks();
