<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_OrganikProcessors_PageSectionHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:addons', 
			array($this, 'get_atts_block_initial_addons'), 
			10, 
			3
		);
		add_filter(
			'GD_Template_Processor_CustomPageTabPageSections:blockunit_intercept_url:source_blocks:addontabs',
			array($this, 'intercepturl_sourceblocks_addontabs')
		);

		add_filter(
			'GD_Template_Processor_CustomPageTabPageSections:blockunit_intercept_url:source_blocks:main', 
			array($this, 'intercepturl_sourceblocks_main')
		);
		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:blockunit_intercept_url:sideinfo_sourceblocks',
			array($this, 'blockunit_intercept_url_sideinfo_sourceblocks')
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
				GD_TEMPLATE_BLOCK_FARM_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_FARM_CREATE,
				GD_TEMPLATE_BLOCK_FARMLINK_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_FARMLINK_CREATE,
			)
		);
	}

	function blockunit_intercept_url_sideinfo_sourceblocks($block_sources) {

		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
			
			return array_merge(
				$block_sources,
				array(
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_FARM_CREATE => GD_TEMPLATE_BLOCK_FARM_CREATE,
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_FARMLINK_CREATE => GD_TEMPLATE_BLOCK_FARMLINK_CREATE,
				)
			);
		}

		return $block_sources;
	}

	function intercepturl_sourceblocks_main($source_blocks) {

		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
		
			return array_merge(
				$source_blocks,
				array(
					GD_TEMPLATE_BLOCK_PAGETABS_FARM_CREATE => GD_TEMPLATE_BLOCK_FARM_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_FARMLINK_CREATE => GD_TEMPLATE_BLOCK_FARMLINK_CREATE,
				)
			);
		}

		return $block_sources;
	}

	function get_atts_block_initial_addons($ret, $subcomponent, $processor) {

		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {

			$notitle = array(
				GD_TEMPLATE_BLOCK_FARM_CREATE,
				GD_TEMPLATE_BLOCK_FARM_UPDATE,
				GD_TEMPLATE_BLOCK_FARMLINK_CREATE,
				GD_TEMPLATE_BLOCK_FARMLINK_UPDATE,
			);
			if (in_array($subcomponent, $notitle)) {
				$processor->add_att($subcomponent, $ret, 'title', '');
			}
		}

		return $ret;
	}

	function intercepturl_sourceblocks_addontabs($source_blocks) {

		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {

			$source_blocks = array_merge(
				$source_blocks,
				array(
					GD_TEMPLATE_BLOCK_PAGETABS_FARM_CREATE => GD_TEMPLATE_BLOCK_FARM_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_FARMLINK_CREATE => GD_TEMPLATE_BLOCK_FARMLINK_CREATE,
				)
			);
		}
		return $source_blocks;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_OrganikProcessors_PageSectionHooks();
