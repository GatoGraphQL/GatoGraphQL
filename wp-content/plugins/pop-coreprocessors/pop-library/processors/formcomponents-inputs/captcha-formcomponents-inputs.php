<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_CAPTCHA', PoP_ServerUtils::get_template_definition('formcomponent-captcha'));

class GD_Template_Processor_CaptchaFormComponentInputs extends GD_Template_Processor_CaptchaFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_CAPTCHA
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CAPTCHA;
				
				return __('Captcha', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CAPTCHA;
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		// Captcha: visible only if user not logged in
		$this->append_att($template_id, $atts, 'class', 'visible-notloggedin');
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CaptchaFormComponentInputs();