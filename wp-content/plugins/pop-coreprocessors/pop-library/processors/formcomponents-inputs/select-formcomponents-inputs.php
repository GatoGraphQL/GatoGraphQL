<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERUSER', PoP_TemplateIDUtils::get_template_definition('order-user', true)); // Keep the name, so the URL params when filtering make sense
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERPOST', PoP_TemplateIDUtils::get_template_definition('order-post', true)); // Keep the name, so the URL params when filtering make sense
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERTAG', PoP_TemplateIDUtils::get_template_definition('order-tag', true)); // Keep the name, so the URL params when filtering make sense
define ('GD_TEMPLATE_FORMCOMPONENT_SETTINGSFORMAT', PoP_TemplateIDUtils::get_template_definition('formcomponent-settingsformat'));

class GD_Template_Processor_SelectFormComponentInputs extends GD_Template_Processor_SelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			// GD_TEMPLATE_FORMCOMPONENT_MULTISELECT,
			GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERUSER,
			GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERPOST,
			GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERTAG,
			GD_TEMPLATE_FORMCOMPONENT_SETTINGSFORMAT,
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERUSER:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERPOST:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERTAG:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERUSER:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERPOST:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERTAG:
			
				return __('Order by', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_SETTINGSFORMAT:

				return __('Default view', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
		
			// case GD_TEMPLATE_FORMCOMPONENT_MULTISELECT:
			
			// 	return new GD_FormInput_MultiSelect($options);

			case GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERUSER:
			
				return new GD_FormInput_OrderUser($options);

			case GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERPOST:
			
				return new GD_FormInput_OrderPost($options);

			case GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERTAG:
			
				return new GD_FormInput_OrderTag($options);

			case GD_TEMPLATE_FORMCOMPONENT_SETTINGSFORMAT:

				return new GD_FormInput_SettingsFormat($options);
		}
		
		return parent::get_input($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SelectFormComponentInputs();