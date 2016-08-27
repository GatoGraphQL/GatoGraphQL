<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES', PoP_ServerUtils::get_template_definition('layout-marknotificationasread-showhideelemstyles'));
define ('GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES', PoP_ServerUtils::get_template_definition('layout-marknotificationasunread-showhideelemstyles'));

class GD_AAL_Template_Processor_ShowHideElemMultiStyleLayouts extends GD_Template_Processor_MultiplesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES,
			GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES:
				
				$ret[] = GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES;
				break;
			
			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES:
				
				$ret[] = GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES;
				$ret[] = GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_ShowHideElemMultiStyleLayouts();