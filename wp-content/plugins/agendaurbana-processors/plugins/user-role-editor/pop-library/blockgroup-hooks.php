<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class AgendaUrbana_URE_BlockGroupHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomBlockGroups:blocks:author_widgetarea',
			array($this, 'authortop_widget_blocks'),
			0
		);
	}

	function authortop_widget_blocks($blocks) {

		// Add the members only for communities
		global $author;
		if (gd_ure_is_community($author)) {
		
			$blocks[] = GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL;
		}
		
		return $blocks;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AgendaUrbana_URE_BlockGroupHooks();
