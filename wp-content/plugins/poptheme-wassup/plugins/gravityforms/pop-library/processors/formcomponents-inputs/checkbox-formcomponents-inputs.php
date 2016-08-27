<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_GF_TEMPLATE_FORMCOMPONENT_CUP_NEWSLETTER', PoP_ServerUtils::get_template_definition('gf-cup-newsletter'));

class GD_GF_Template_Processor_CheckboxFormComponentInputs extends GD_Template_Processor_CheckboxFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_GF_TEMPLATE_FORMCOMPONENT_CUP_NEWSLETTER,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_GF_TEMPLATE_FORMCOMPONENT_CUP_NEWSLETTER:

				return __('Subscribe to our Newsletter?', 'poptheme-wassup');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_GF_TEMPLATE_FORMCOMPONENT_CUP_NEWSLETTER:

				if (!$this->load_itemobject_value($template_id, $atts)) {

					// Subscribe to newsletter by default
					$this->add_att($template_id, $atts, 'selected', true);
				}
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_GF_Template_Processor_CheckboxFormComponentInputs();