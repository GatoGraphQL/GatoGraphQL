<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_STATUS', PoP_TemplateIDUtils::get_template_definition('status'));

class GD_Template_Processor_Status extends GD_Template_Processor_StatusBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_STATUS,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_Status();