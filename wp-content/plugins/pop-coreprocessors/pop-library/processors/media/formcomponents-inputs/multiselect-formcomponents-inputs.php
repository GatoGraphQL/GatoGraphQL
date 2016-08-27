<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERFORMCOMPONENT_MIMETYPE', PoP_ServerUtils::get_template_definition('mimetype', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_TAXONOMY', PoP_ServerUtils::get_template_definition('taxonomy', true));

class GD_Template_Processor_MediaMultiSelectFormComponentInputs extends GD_Template_Processor_MultiSelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERFORMCOMPONENT_MIMETYPE,
			GD_TEMPLATE_FILTERFORMCOMPONENT_TAXONOMY
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_MIMETYPE:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_TAXONOMY:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_MIMETYPE:
			
				return __('Resource type', 'pop-coreprocessors');

			case GD_TEMPLATE_FILTERFORMCOMPONENT_TAXONOMY:
			
				return __('Is Resource', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
		
			case GD_TEMPLATE_FILTERFORMCOMPONENT_MIMETYPE:
			
				return new GD_FormInput_MimeType($options);

			case GD_TEMPLATE_FILTERFORMCOMPONENT_TAXONOMY:
			
				return new GD_FormInput_Taxonomy($options);
		}
		
		return parent::get_input($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MediaMultiSelectFormComponentInputs();