<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoad_FormActionExecuterBase extends GD_DataLoad_ActionExecuter {

	function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		// If the post has been submitted, execute the Gravity Forms shortcode
		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			// Before submitting the form, validate the captcha (otherwise, the form is submitted independently of the result of this validation)
			$captcha_validation = $this->validate_captcha($block_data_settings, $block_atts);
			if (is_wp_error($captcha_validation)) {
				
				return $this->get_captcha_error($captcha_validation);
			}

			return $this->execute_form($block_data_settings, $block_atts, $block_execution_bag);
		}

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}

	protected function validate_captcha($block_data_settings, $atts) {

		$iohandler_atts = $block_data_settings['iohandler-atts'];

		// Check if Captcha validation is needed
		if ($iohandler_atts && $iohandler_atts[GD_DATALOAD_IOHANDLER_FORM_VALIDATECAPTCHA]) {

			global $gd_template_processor_manager;
			$captcha = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CAPTCHA)->get_value(GD_TEMPLATE_FORMCOMPONENT_CAPTCHA, $atts);

			return GD_Captcha::validate($captcha['input'], $captcha['session']);
		}

		return true;
	}

	protected function get_captcha_error($captcha_error) {

		return array(
			GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => array($captcha_error->get_error_message())
		);
	}

	protected function execute_form(&$block_data_settings, $block_atts, &$block_execution_bag) {

		return array(
			GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
		);	
	}
}
