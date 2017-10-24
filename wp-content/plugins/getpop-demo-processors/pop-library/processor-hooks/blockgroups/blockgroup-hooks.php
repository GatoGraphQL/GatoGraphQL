<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GetPoPDemo_Processors_BlockGroupHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_MainBlockGroups:blockgroups:home_tops',
			array($this, 'home_topblockgroups')
		);
		add_filter(
			'GD_Template_Processor_CustomBlockGroups:blocks:author_widgetarea',
			array($this, 'authortop_widget_blocks'),
			3
		);
		add_filter(
			'GD_Template_Processor_CustomBlockGroups:blocks:tag_widgetarea',
			array($this, 'tagtop_widget_blocks'),
			3
		);
		add_filter(
			'GD_Template_Processor_CustomBlockGroups:blocks:atts',
			array($this, 'block_atts'),
			10,
			5
		);
	}

	function block_atts($blockgroup_block_atts, $blockgroup_block, $blockgroup, $blockgroup_atts, $processor) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA:
			case GD_TEMPLATE_BLOCKGROUP_TAG_WIDGETAREA:

				// Hide if block is empty
				$processor->add_att($blockgroup_block, $blockgroup_block_atts, 'hidden-if-empty', true);

				if (
					$blockgroup_block == GD_TEMPLATE_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP ||
					$blockgroup_block == GD_TEMPLATE_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP
					) {

					// Format
					$processor->add_att($blockgroup_block, $blockgroup_block_atts, 'title-htmltag', 'h3');
					$processor->add_att($blockgroup_block, $blockgroup_block_atts, 'add-titlelink', true);
					$processor->add_att($blockgroup_block, $blockgroup_block_atts, 'collapsible', true);
				}
				break;
		}

		return $blockgroup_block_atts;
	}

	
	function home_topblockgroups($blockgroups) {

		return array(
			GETPOPDEMO_TEMPLATE_BLOCKGROUP_HOME
			// GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME
		);
	}

	function authortop_widget_blocks($blocks) {

		// Add the Blockgroup which has the Featured widget
		$blocks[] = GD_TEMPLATE_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP;
		return $blocks;
	}

	function tagtop_widget_blocks($blocks) {

		// Add the Blockgroup which has the Featured widget
		$blocks[] = GD_TEMPLATE_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP;
		return $blocks;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoPDemo_Processors_BlockGroupHooks();
