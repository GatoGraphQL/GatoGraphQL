<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_CategoryProcessors_BlockHooks {

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

		// Add the sidebars only for the independent categories; section categories already get this setting from WEBPOST BLOCK
		foreach (PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cats(array(POP_CATEGORYPROCESSORS_CONFIGUTILS_POSTS)) as $cat) {
			$sidebars[$cat] = GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_WEBPOST;
		}
		
		return $sidebars;
	}

	function bottomsidebars($sidebars) {

		// Add the sidebars only for the independent categories; section categories already get this setting from WEBPOST BLOCK
		foreach (PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cats(array(POP_CATEGORYPROCESSORS_CONFIGUTILS_POSTS)) as $cat) {
			$sidebars[$cat] = GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL;
		}
		
		return $sidebars;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_CategoryProcessors_BlockHooks();
