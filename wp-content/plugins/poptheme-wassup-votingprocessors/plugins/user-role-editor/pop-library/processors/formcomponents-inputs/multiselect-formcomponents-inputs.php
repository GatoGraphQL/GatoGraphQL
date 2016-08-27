<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERFORMCOMPONENT_AUTHORROLE_MULTISELECT', PoP_ServerUtils::get_template_definition('authorrolemulti', true));

class VotingProcessors_URE_Template_Processor_MultiSelectFormComponentInputs extends GD_Template_Processor_MultiSelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERFORMCOMPONENT_AUTHORROLE_MULTISELECT
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_AUTHORROLE_MULTISELECT:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_AUTHORROLE_MULTISELECT:

				return __('Author Role', 'poptheme-wassup-votingprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
						
			case GD_TEMPLATE_FILTERFORMCOMPONENT_AUTHORROLE_MULTISELECT:

				return new GD_URE_FormInput_MultiAuthorRole($options);
		}
		
		return parent::get_input($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_URE_Template_Processor_MultiSelectFormComponentInputs();