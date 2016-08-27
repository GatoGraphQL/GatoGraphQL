<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CheckboxFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_CHECKBOX;
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		return new GD_FormInput_Checkbox($options);
	}
}
