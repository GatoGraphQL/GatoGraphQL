<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_OrganikProcessors_BlockHooks {

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

		// If a category was kept in false then it is not fed to allcontent. Eg: Farms not needed for TPP Debate
		$cat_sidebars = array(
			POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_FARM,
		);
		foreach ($cat_sidebars as $cat => $sidebar) {
			if ($cat) {
				$sidebars[$cat] = $sidebar;
			}
		}
		
		return $sidebars;
	}

	function bottomsidebars($sidebars) {

		// If a category was kept in false then it is not fed to allcontent. Eg: Farms not needed for TPP Debate
		$cat_sidebars = array(
			POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
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
new PoPTheme_Wassup_OrganikProcessors_BlockHooks();
