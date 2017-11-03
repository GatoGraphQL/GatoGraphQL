<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES_TRIGGER', PoP_TemplateIDUtils::get_template_definition('formcomponent-selectabletypeaheadtrigger-references'));

class GD_Template_Processor_PostSelectableTypeaheadTriggerFormComponentInputs extends GD_Template_Processor_PostSelectableTypeaheadTriggerFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES_TRIGGER
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostSelectableTypeaheadTriggerFormComponentInputs();