<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_VotingProcessors_PageSectionHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomPageTabPageSections:blockunit_intercept_url:source_blocks:addontabs',
			array($this, 'intercepturl_sourceblocks_addontabs')
		);
		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:addons', 
			array($this, 'get_atts_block_initial_addons'), 
			10, 
			3
		);

		add_filter(
			'GD_Template_Processor_BootstrapPageSectionsBase:replicate_blocksettingsids',
			array($this, 'replicate_blocksettingsids')
		);
	}

	function replicate_blocksettingsids($block_frames) {

		return array_merge(
			$block_frames,
			array(
				GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_OPINIONATEDVOTE_CREATE,
			)
		);
	}

	function get_atts_block_initial_addons($ret, $subcomponent, $processor) {

		$notitle = array(
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE,
		);
		if (in_array($subcomponent, $notitle)) {
			$processor->add_att($subcomponent, $ret, 'title', '');
		}

		return $ret;
	}

	function intercepturl_sourceblocks_addontabs($source_blocks) {

		return array_merge(
			$source_blocks,
			array(
				GD_TEMPLATE_BLOCK_PAGETABS_OPINIONATEDVOTE_CREATE => GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE,
			)
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_VotingProcessors_PageSectionHooks();
