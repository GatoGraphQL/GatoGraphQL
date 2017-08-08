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

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'addDomainClass');
		return $ret;
	}

	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		// For function addDomainClass
		$ret['prefix'] = 'visible-notloggedin-';

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Captcha: visible only if user not logged in
		// $this->append_att($template_id, $atts, 'class', 'visible-notloggedin');
		// $this->append_att($template_id, $atts, 'wrapper-class', 'visible-notloggedin');

		// If we don't use the loggedinuser-data, then show the inputs always
		if (!PoP_FormUtils::use_loggedinuser_data()) {
			// $this->append_att($template_id, $atts, 'class', 'visible-always');
			$this->append_att($template_id, $atts, 'wrapper-class', 'visible-always');
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CaptchaFormComponentInputs();