<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_CONTENT_LINK', PoP_TemplateIDUtils::get_template_definition('layout-content-link'));

class GD_Template_Processor_LinkContentLayouts extends GD_Template_Processor_LinkContentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_CONTENT_LINK,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LinkContentLayouts();