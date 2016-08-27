<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PageSectionHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_PageSectionsBase:get_atts_block_initial', 
			array($this, 'get_atts_block_initial'), 
			10,
			3
		);
	}

	function get_atts_block_initial($block_atts, $template_id, $subcomponent) {

		global $gd_template_processor_manager;

		// Submenu
		$block_processor = $gd_template_processor_manager->get_processor($subcomponent);
		$submenu = $block_processor->get_submenu($subcomponent);

		$block_atts['submenu'] = $submenu;
		
		return $block_atts;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PageSectionHooks();
