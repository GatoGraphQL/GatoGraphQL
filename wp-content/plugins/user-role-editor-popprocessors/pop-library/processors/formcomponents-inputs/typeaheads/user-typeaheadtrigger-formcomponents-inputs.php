<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_TRIGGER', PoP_TemplateIDUtils::get_template_definition('formcomponent-selectabletypeaheadtrigger-communities'));

class GD_URE_Template_Processor_UserSelectableTypeaheadTriggerFormComponentInputs extends GD_Template_Processor_UserSelectableTypeaheadTriggerFormComponentInputs {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_TRIGGER
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_UserSelectableTypeaheadTriggerFormComponentInputs();