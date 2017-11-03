<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MENU_INDENT', PoP_TemplateIDUtils::get_template_definition('layout-menu-indent'));

class GD_Template_Processor_IndentMenuLayouts extends GD_Template_Processor_IndentMenuLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MENU_INDENT
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_IndentMenuLayouts();