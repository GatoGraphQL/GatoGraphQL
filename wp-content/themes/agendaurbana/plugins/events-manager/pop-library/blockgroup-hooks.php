<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class AgendaUrbana_EM_BlockGroupHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomBlockGroups:blocks:home_widgetarea',
			array($this, 'hometop_widget_blocks'),
			10
		);
		add_filter(
			'GD_Template_Processor_CustomBlockGroups:blocks:author_widgetarea',
			array($this, 'authortop_widget_blocks'),
			10
		);
		add_filter(
			'GD_Template_Processor_CustomBlockGroups:blocks:tag_widgetarea',
			array($this, 'tagtop_widget_blocks'),
			10
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

			case GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA:

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_EVENTS_CAROUSEL) {
					
					// Hide if block is empty
					$processor->add_att($blockgroup_block, $blockgroup_block_atts, 'hidden-if-empty', true);
				}
				break;
		}

		return $blockgroup_block_atts;
	}

	function hometop_widget_blocks($blocks) {

		$blocks[] = GD_TEMPLATE_BLOCK_EVENTS_CAROUSEL;
		return $blocks;
	}

	function authortop_widget_blocks($blocks) {

		$blocks[] = GD_TEMPLATE_BLOCK_AUTHOREVENTS_CAROUSEL;		
		return $blocks;
	}

	function tagtop_widget_blocks($blocks) {

		$blocks[] = GD_TEMPLATE_BLOCK_TAGEVENTS_CAROUSEL;		
		return $blocks;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AgendaUrbana_EM_BlockGroupHooks();
