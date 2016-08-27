<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_DROPDOWNMENU', PoP_ServerUtils::get_template_definition('dropdownmenu'));

class GD_Template_Processor_DropdownMenus extends GD_Template_Processor_DropdownMenusBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_DROPDOWNMENU
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_DropdownMenus();