<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_MULTISELECT', PoP_TemplateIDUtils::get_template_definition('formcomponent-volunteersneededmulti'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_VOLUNTEERSNEEDED_MULTISELECT', PoP_TemplateIDUtils::get_template_definition('volunteersneededmulti', true));

class PoPTheme_Wassup_Template_Processor_MultiSelectFormComponentInputs extends GD_Template_Processor_MultiSelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_MULTISELECT,
			GD_TEMPLATE_FILTERFORMCOMPONENT_VOLUNTEERSNEEDED_MULTISELECT
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_VOLUNTEERSNEEDED_MULTISELECT:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_MULTISELECT:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_VOLUNTEERSNEEDED_MULTISELECT:

				return __('Volunteers Needed?', 'poptheme-wassup');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
						
			case GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_MULTISELECT:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_VOLUNTEERSNEEDED_MULTISELECT:

				return new GD_FormInput_MultiYesNo($options);
		}
		
		return parent::get_input($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_Template_Processor_MultiSelectFormComponentInputs();