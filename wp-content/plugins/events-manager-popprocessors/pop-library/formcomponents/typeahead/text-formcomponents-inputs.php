<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADADDLOCATION', PoP_ServerUtils::get_template_definition('formcomponent-text-typeaheadaddlocation'));

class GD_EM_Template_Processor_TextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADADDLOCATION,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADADDLOCATION:

				return __('Name or Address', 'em-popprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_TextFormComponentInputs();