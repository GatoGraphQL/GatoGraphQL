<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SEGMENTEDBUTTONMENU', PoP_TemplateIDUtils::get_template_definition('segmentedbuttonmenu'));
define ('GD_TEMPLATE_NAVIGATORSEGMENTEDBUTTONMENU', PoP_TemplateIDUtils::get_template_definition('navigatorsegmentedbuttonmenu'));

// class GD_Template_Processor_SegmentedButtonMenus extends GD_Template_Processor_SegmentedButtonMenusBase {
class GD_Template_Processor_SegmentedButtonMenus extends GD_Template_Processor_ContentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SEGMENTEDBUTTONMENU,
			GD_TEMPLATE_NAVIGATORSEGMENTEDBUTTONMENU
		);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SEGMENTEDBUTTONMENU:

				return GD_TEMPLATE_CONTENTINNER_MENU_SEGMENTEDBUTTON;

			case GD_TEMPLATE_NAVIGATORSEGMENTEDBUTTONMENU:
				
				return GD_TEMPLATE_CONTENTINNER_MENU_NAVIGATORSEGMENTEDBUTTON;				
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SegmentedButtonMenus();