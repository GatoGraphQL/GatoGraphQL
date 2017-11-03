<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_EM_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION', PoP_TemplateIDUtils::get_template_definition('em-script-triggertypeaheadselect-location'));

class GD_Template_Processor_TriggerLocationTypeaheadScriptLayouts extends GD_Template_Processor_TriggerLocationTypeaheadScriptLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_EM_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TriggerLocationTypeaheadScriptLayouts();