<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_GF_PageSectionHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomPageTabPageSections:blockunit_intercept_url:source_blocks:addontabs',
			array($this, 'intercepturl_sourceblocks_addontabs')
		);
		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:hover', 
			array($this, 'get_atts_block_initial_hover'), 
			10, 
			3
		);
		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:addons', 
			array($this, 'get_atts_block_initial_addons'), 
			10, 
			3
		);
		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_header_titles:addons',
			array($this, 'header_titles')
		);
	}

	function header_titles($header_titles) {

		return array_merge(
			$header_titles,
			array(
				GD_TEMPLATE_BLOCK_CONTACTUSER => __('Send message to:', 'poptheme-wassup'),
				GD_TEMPLATE_BLOCK_VOLUNTEER => __('Volunteer for:', 'poptheme-wassup'),
				GD_TEMPLATE_BLOCK_FLAG => __('Flag as inappropriate:', 'poptheme-wassup'),
			)
		);
	}

	function get_atts_block_initial_hover($ret, $subcomponent, $processor) {

		$resetlogout_blocks = array(
			GD_TEMPLATE_BLOCK_CONTACTUS,
		);
		if (in_array($subcomponent, $resetlogout_blocks)) {
			$processor->merge_block_jsmethod_att($subcomponent, $ret, array('resetOnUserLogout'));
		}

		return $ret;
	}

	function get_atts_block_initial_addons($ret, $subcomponent, $processor) {

		$notitle_blocks = array(
			GD_TEMPLATE_BLOCK_CONTACTUSER,
			GD_TEMPLATE_BLOCK_VOLUNTEER,
			GD_TEMPLATE_BLOCK_FLAG,
		);
		if (in_array($subcomponent, $notitle_blocks)) {
			$processor->add_att($subcomponent, $ret, 'title', '');
		}
		$destroy_blocks = array(
			GD_TEMPLATE_BLOCK_CONTACTUSER,
			GD_TEMPLATE_BLOCK_VOLUNTEER,
			GD_TEMPLATE_BLOCK_FLAG,
		);
		$delay = array(
			GD_TEMPLATE_BLOCK_CONTACTUSER,
			GD_TEMPLATE_BLOCK_VOLUNTEER,
			GD_TEMPLATE_BLOCK_FLAG,
		);
		if (in_array($subcomponent, $destroy_blocks)) {
			$processor->merge_block_jsmethod_att($subcomponent, $ret, array('destroyPageOnSuccess'));
			if (in_array($subcomponent, $delay)) {
				$processor->merge_att($subcomponent, $ret, 'params', array(
					'data-destroytime' => 3000
				));
			}
		}

		return $ret;
	}

	function intercepturl_sourceblocks_addontabs($source_blocks) {

		return array_merge(
			$source_blocks,
			array(
				GD_TEMPLATE_BLOCK_ADDONTABS_CONTACTUSER => GD_TEMPLATE_BLOCK_CONTACTUSER,
				GD_TEMPLATE_BLOCK_ADDONTABS_VOLUNTEER => GD_TEMPLATE_BLOCK_VOLUNTEER,
				GD_TEMPLATE_BLOCK_ADDONTABS_FLAG => GD_TEMPLATE_BLOCK_FLAG,
			)
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_GF_PageSectionHooks();
