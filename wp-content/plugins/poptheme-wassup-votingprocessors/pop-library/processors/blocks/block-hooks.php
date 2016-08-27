<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_VotingProcessors_BlockHooks {

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

		$sidebars[POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES] = GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_OPINIONATEDVOTE;
		return $sidebars;
	}

	function bottomsidebars($sidebars) {

		$sidebars[POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES] = GD_TEMPLATE_CONTENT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL;
		return $sidebars;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_VotingProcessors_BlockHooks();
