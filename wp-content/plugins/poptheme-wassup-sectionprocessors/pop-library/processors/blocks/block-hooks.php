<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_SectionProcessors_BlockHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomContentBlocks:get_block_inner_templates:content:sidebar_by_category',
			array($this, 'sidebars')
		);
		add_filter(
			'GD_Template_Processor_CustomContentBlocks:get_block_inner_templates:content:bottomsidebar_by_category',
			array($this, 'bottomsidebars')
		);
	}

	function sidebars($sidebars) {

		// If a category was kept in false then it is not fed to allcontent. Eg: Projects not needed for TPP Debate
		$cat_sidebars = array(
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PROJECT,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STORY,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_ANNOUNCEMENT,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_DISCUSSION,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_FEATURED,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_BLOG => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_BLOG,
		);
		foreach ($cat_sidebars as $cat => $sidebar) {
			if ($cat) {
				$sidebars[$cat] = $sidebar;
			}
		}
		
		return $sidebars;
	}

	function bottomsidebars($sidebars) {

		// If a category was kept in false then it is not fed to allcontent. Eg: Projects not needed for TPP Debate
		$cat_sidebars = array(
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_BLOG => GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
		);
		foreach ($cat_sidebars as $cat => $sidebar) {
			if ($cat) {
				$sidebars[$cat] = $sidebar;
			}
		}
		
		return $sidebars;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_SectionProcessors_BlockHooks();
