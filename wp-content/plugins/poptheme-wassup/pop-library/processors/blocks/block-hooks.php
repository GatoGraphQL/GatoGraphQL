<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_BlockHooks {

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

	function bottomsidebars($sidebars) {

		// $sidebars[POPTHEME_WASSUP_CAT_WEBPOSTLINKS] = GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL;
		$sidebars[POPTHEME_WASSUP_CAT_WEBPOSTS] = GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL;
		$sidebars[POPTHEME_WASSUP_CAT_HIGHLIGHTS] = GD_TEMPLATE_CONTENT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL;
		return $sidebars;
	}

	function sidebars($sidebars) {

		// $sidebars[POPTHEME_WASSUP_CAT_WEBPOSTLINKS] = GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK;
		$sidebars[POPTHEME_WASSUP_CAT_WEBPOSTS] = GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_WEBPOST;
		$sidebars[POPTHEME_WASSUP_CAT_HIGHLIGHTS] = GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT;
		return $sidebars;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_BlockHooks();
