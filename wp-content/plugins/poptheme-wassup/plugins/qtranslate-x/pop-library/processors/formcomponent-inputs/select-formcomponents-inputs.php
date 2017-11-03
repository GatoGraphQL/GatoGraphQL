<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_QT_TEMPLATE_FORMCOMPONENT_LANGUAGE', PoP_TemplateIDUtils::get_template_definition('qt-formcomponent-language'));

class GD_QT_Template_Processor_SelectFormComponentInputs extends GD_Template_Processor_SelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_QT_TEMPLATE_FORMCOMPONENT_LANGUAGE
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_QT_TEMPLATE_FORMCOMPONENT_LANGUAGE:

				return __('Language', 'poptheme-wassup');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {

			case GD_QT_TEMPLATE_FORMCOMPONENT_LANGUAGE:

				return new GD_QT_FormInput_Languages($options);
		}
		
		return parent::get_input($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_QT_Template_Processor_SelectFormComponentInputs();