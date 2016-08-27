<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADORGANIZATIONS', PoP_ServerUtils::get_template_definition('formcomponent-text-typeaheadorganizations'));

class GD_URE_Template_Processor_TypeaheadTextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADORGANIZATIONS,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADORGANIZATIONS:
		
				return __('Organization', 'ure-popprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_TypeaheadTextFormComponentInputs();