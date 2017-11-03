<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_EM_LAYOUTEVENT_TABLECOL', PoP_TemplateIDUtils::get_template_definition('em-layoutevent-tablecol'));

class GD_Template_Processor_EventDateAndTimeLayouts extends GD_Template_Processor_EventDateAndTimeLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_EM_LAYOUTEVENT_TABLECOL,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_EventDateAndTimeLayouts();