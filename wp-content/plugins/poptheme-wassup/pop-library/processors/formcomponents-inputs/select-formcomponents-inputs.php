<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_SELECT', PoP_ServerUtils::get_template_definition('formcomponent-custom-volunteersneeded'));

class GD_Custom_Template_Processor_SelectFormComponentInputs extends GD_Template_Processor_SelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_SELECT
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_SELECT:

				return __('Volunteers Needed?', 'poptheme-wassup');
		}
		
		return parent::get_label_text($template_id, $atts);
	}
	// function get_info($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_SELECT:

	// 			return __('Do you need volunteers? Each time a user applies to volunteer, you will get a notification email with the volunteer\'s contact information.', 'poptheme-wassup');
	// 	}
		
	// 	return parent::get_info($template_id, $atts);
	// }

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_SELECT:

				return new GD_FormInput_YesNo($options);
		}
		
		return parent::get_input($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_SELECT:
				
				$ret[] = array('key' => 'value', 'field' => 'volunteers-needed-string');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_SelectFormComponentInputs();