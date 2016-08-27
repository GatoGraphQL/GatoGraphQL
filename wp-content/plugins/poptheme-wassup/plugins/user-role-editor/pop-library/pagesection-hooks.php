<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_URE_PageSectionHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:hover', 
			array($this, 'get_atts_block_initial_hover'), 
			10, 
			3
		);
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

	function modal_header_titles($header_titles) {

		return array_merge(
			$header_titles,
			array(
				GD_TEMPLATE_BLOCK_INVITENEWMEMBERS => get_the_title(POP_URE_POPPROCESSORS_PAGE_INVITENEWMEMBERS),
			)
		);
	}

	function get_atts_block_initial_modals($ret, $subcomponent, $processor) {

		if ($subcomponent == GD_TEMPLATE_BLOCK_INVITENEWMEMBERS) {

			// Hide the Title
			$processor->add_att($subcomponent, $ret, 'title', '');
		}		

		return $ret;
	}

	function get_atts_block_initial_hover($ret, $subcomponent, $processor) {

		$resetsuccess_blocks = array(
			GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_CREATE,
			GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_CREATE,
		);
		if (in_array($subcomponent, $resetsuccess_blocks)) {
			$processor->merge_block_jsmethod_att($subcomponent, $ret, array('resetOnSuccess'));
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_URE_PageSectionHooks();
