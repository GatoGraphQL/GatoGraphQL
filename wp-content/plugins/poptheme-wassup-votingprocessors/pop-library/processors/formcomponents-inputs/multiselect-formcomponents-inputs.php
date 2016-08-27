<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_STANCE_MULTISELECT', PoP_ServerUtils::get_template_definition('formcomponent-stancemulti'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_STANCE_MULTISELECT', PoP_ServerUtils::get_template_definition('stancemulti', true));

class VotingProcessors_Template_Processor_MultiSelectFormComponentInputs extends GD_Template_Processor_MultiSelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_STANCE_MULTISELECT,
			GD_TEMPLATE_FILTERFORMCOMPONENT_STANCE_MULTISELECT
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_STANCE_MULTISELECT:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_STANCE_MULTISELECT:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_STANCE_MULTISELECT:

				return __('Stance', 'poptheme-wassup-votingprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
						
			case GD_TEMPLATE_FORMCOMPONENT_STANCE_MULTISELECT:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_STANCE_MULTISELECT:

				return new GD_FormInput_MultiStance($options);
		}
		
		return parent::get_input($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_MultiSelectFormComponentInputs();