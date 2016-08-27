<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GetPoP_PageSectionHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:sideinfo', 
			array($this, 'get_atts_block_initial_sideinfo'), 
			10, 
			3
		);
	}

	function get_atts_block_initial_sideinfo($ret, $subcomponent, $processor) {

		if ($subcomponent == GD_TEMPLATE_BLOCK_WEBSITEFEATURES_IDEALFORIMPLEMENTING) {
			
			// Formatting
			$processor->add_att($subcomponent, $ret, 'title-htmltag', 'h4');
			$processor->add_att(GD_TEMPLATE_CODE_WEBSITEFEATURES_IDEALFORIMPLEMENTING, $ret, 'firstitem-open', false);
			$processor->add_att(GD_TEMPLATE_CODE_WEBSITEFEATURES_IDEALFORIMPLEMENTING, $ret, 'panel-class', 'panel panel-default');
		}		

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_PageSectionHooks();
