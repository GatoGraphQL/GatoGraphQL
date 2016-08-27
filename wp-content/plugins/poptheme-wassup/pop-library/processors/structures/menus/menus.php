<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_DROPDOWNBUTTONMENU_TOP', PoP_ServerUtils::get_template_definition('dropdownbuttonmenu-top'));
define ('GD_TEMPLATE_DROPDOWNBUTTONMENU_SIDE', PoP_ServerUtils::get_template_definition('dropdownbuttonmenu-side'));
define ('GD_TEMPLATE_MULTITARGETINDENTMENU', PoP_ServerUtils::get_template_definition('multitargetindentmenu'));

class GD_Template_Processor_Menus extends GD_Template_Processor_ContentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_DROPDOWNBUTTONMENU_TOP,
			GD_TEMPLATE_DROPDOWNBUTTONMENU_SIDE,
			GD_TEMPLATE_MULTITARGETINDENTMENU,
		);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_DROPDOWNBUTTONMENU_TOP:
				
				return GD_TEMPLATE_CONTENTINNER_MENU_DROPDOWNBUTTON_TOP;
			
			case GD_TEMPLATE_DROPDOWNBUTTONMENU_SIDE:
				
				return GD_TEMPLATE_CONTENTINNER_MENU_DROPDOWNBUTTON_SIDE;

			case GD_TEMPLATE_MULTITARGETINDENTMENU:

				return GD_TEMPLATE_CONTENTINNER_MENU_MULTITARGETINDENT;
		}

		return get_inner_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_Menus();