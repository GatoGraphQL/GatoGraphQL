<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS_TRIGGER', PoP_ServerUtils::get_template_definition('formcomponent-selectabletypeaheadtrigger-locations'));

class GD_Template_Processor_LocationSelectableTypeaheadTriggerFormComponentInputs extends GD_Template_Processor_LocationSelectableTypeaheadTriggerFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS_TRIGGER,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LocationSelectableTypeaheadTriggerFormComponentInputs();