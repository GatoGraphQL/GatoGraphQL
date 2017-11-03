<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_INDENTMENU', PoP_TemplateIDUtils::get_template_definition('indentmenu'));

class GD_Template_Processor_IndentMenus extends GD_Template_Processor_IndentMenusBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_INDENTMENU
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_IndentMenus();