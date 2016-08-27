<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONCOUNTRY', PoP_ServerUtils::get_template_definition('location_country', true)); // Name needed by EM to create the Location

class GD_EM_Template_Processor_CreateLocationSelectFormComponentInputs extends GD_Template_Processor_SelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONCOUNTRY
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONCOUNTRY:

				return __('Country', 'em-popprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONCOUNTRY:
			
				return new GD_FormInput_EM_LocationCountries($options);
		}
		
		return parent::get_input($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONCOUNTRY:
				
				$this->append_att($template_id, $atts, 'class', 'address-input');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CreateLocationSelectFormComponentInputs();