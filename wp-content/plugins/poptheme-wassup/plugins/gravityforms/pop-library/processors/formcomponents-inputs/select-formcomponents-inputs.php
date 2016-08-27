<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_GF_TEMPLATE_FORMCOMPONENT_TOPIC', PoP_ServerUtils::get_template_definition('gf-field-topic'));
// define ('GD_GF_TEMPLATE_FORMCOMPONENT_CUP_NEWSLETTER', 'gf-cup-newsletter');

class GD_GF_Template_Processor_SelectFormComponentInputs extends GD_Template_Processor_SelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_GF_TEMPLATE_FORMCOMPONENT_TOPIC,
			// GD_GF_TEMPLATE_FORMCOMPONENT_CUP_NEWSLETTER,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			// case GD_GF_TEMPLATE_FORMCOMPONENT_CUP_NEWSLETTER:
				
			// 	return __('Subscribe to our Newsletter?', 'poptheme-wassup');
				
			case GD_GF_TEMPLATE_FORMCOMPONENT_TOPIC:
			
				return __('Topic', 'poptheme-wassup');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
		
			case GD_GF_TEMPLATE_FORMCOMPONENT_TOPIC:
			
				return new GD_FormInput_ContactUs_Topics($options);

			// case GD_GF_TEMPLATE_FORMCOMPONENT_CUP_NEWSLETTER:
			
			// 	return new GD_FormInput_YesNo($options);
		}
		
		return parent::get_input($template_id, $atts);
	}

	// function init_atts($template_id, &$atts) {
	
	// 	switch ($template_id) {
		
	// 		case GD_GF_TEMPLATE_FORMCOMPONENT_CUP_NEWSLETTER:

	// 			if (!$this->get_att($template_id, $atts, 'load-itemobject-value')) {

	// 				// Set default value on Yes
	// 				$this->add_att($template_id, $atts, 'selected', true);
	// 			}
	// 			break;
	// 	}
		
	// 	return parent::init_atts($template_id, $atts);
	// }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_GF_Template_Processor_SelectFormComponentInputs();