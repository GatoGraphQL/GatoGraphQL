<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_EM_LAYOUT_LOCATIONS', PoP_TemplateIDUtils::get_template_definition('em-layout-locations'));

class GD_EM_Template_Processor_LocationLayouts extends GD_EM_Template_Processor_LocationLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_EM_LAYOUT_LOCATIONS,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_LocationLayouts();