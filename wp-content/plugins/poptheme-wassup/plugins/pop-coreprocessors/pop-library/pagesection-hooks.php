<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_PoPCore_PageSectionHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomModalPageSections:get_header_titles:modals',
			array($this, 'modal_header_titles')
		);
		add_filter(
			'GD_Template_Processor_CustomModalPageSections:get_atts_block_initial:modals', 
			array($this, 'get_atts_block_initial_modals'), 
			10, 
			3
		);
	}

	function get_atts_block_initial_modals($ret, $subcomponent, $processor) {

		if ($subcomponent == GD_TEMPLATE_BLOCK_INVITENEWUSERS) {

			// Hide the Title
			$processor->add_att($subcomponent, $ret, 'title', '');
		}		

		return $ret;
	}

	function modal_header_titles($header_titles) {

		return array_merge(
			$header_titles,
			array(
				GD_TEMPLATE_BLOCK_INVITENEWUSERS => get_the_title(POP_COREPROCESSORS_PAGE_INVITENEWUSERS),
			)
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_PoPCore_PageSectionHooks();
