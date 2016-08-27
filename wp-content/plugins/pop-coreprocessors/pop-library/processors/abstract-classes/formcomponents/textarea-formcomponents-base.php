<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TextareaFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_TEXTAREA;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['rows'] = $this->get_rows($template_id, $atts);
		if ($placeholder = $this->get_att($template_id, $atts, 'placeholder')) {

			$ret['placeholder'] = $placeholder;
		}
				
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Use the label as placeholder
		$this->add_att($template_id, $atts, 'placeholder', $this->get_label($template_id, $atts));
		return parent::init_atts($template_id, $atts);
	}

	function get_rows($template_id, $atts) {

		return 5;
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		return new GD_FormInput($options);
	}
}
