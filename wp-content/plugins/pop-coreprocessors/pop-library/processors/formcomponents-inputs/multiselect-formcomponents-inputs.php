<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERFORMCOMPONENT_MODERATEDPOSTSTATUS', PoP_TemplateIDUtils::get_template_definition('moderatedpoststatus', true)); // Keep the name, so the URL params when filtering make sense
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_UNMODERATEDPOSTSTATUS', PoP_TemplateIDUtils::get_template_definition('unmoderatedpoststatus', true)); // Keep the name, so the URL params when filtering make sense

class GD_Template_Processor_MultiSelectFormComponentInputs extends GD_Template_Processor_MultiSelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERFORMCOMPONENT_MODERATEDPOSTSTATUS,
			GD_TEMPLATE_FILTERFORMCOMPONENT_UNMODERATEDPOSTSTATUS,
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_MODERATEDPOSTSTATUS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_UNMODERATEDPOSTSTATUS:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_MODERATEDPOSTSTATUS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_UNMODERATEDPOSTSTATUS:

				return __('Status', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_MODERATEDPOSTSTATUS:

				return new GD_FormInput_ModeratedStatus($options);

			case GD_TEMPLATE_FILTERFORMCOMPONENT_UNMODERATEDPOSTSTATUS:

				return new GD_FormInput_UnmoderatedStatus($options);
		}
		
		return parent::get_input($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MultiSelectFormComponentInputs();