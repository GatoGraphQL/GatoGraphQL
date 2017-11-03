<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MENU_DROPDOWN', PoP_TemplateIDUtils::get_template_definition('layout-menu-dropdown'));

class GD_Template_Processor_DropdownMenuLayouts extends GD_Template_Processor_DropdownMenuLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MENU_DROPDOWN,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_DropdownMenuLayouts();