<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FormActionsBase extends GD_Template_Processor_ActionsBase {
	
	// Comment Leo 12/01/2017: make it runtime instead of static, since it needs to validate if the user is logged in
	// function get_data_setting($template_id, $atts) {
	function get_runtime_datasetting($template_id, $atts) {

		// $ret = parent::get_data_setting($template_id, $atts);
		$ret = parent::get_runtime_datasetting($template_id, $atts);
	
		// Check if needed to validate Captcha
		if ($this->validate_captcha($template_id, $atts)) {

			$vars = GD_TemplateManager_Utils::get_vars();
			if (!(PoP_FormUtils::use_loggedinuser_data() && $vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/)) {
				$ret['iohandler-atts'][GD_DATALOAD_IOHANDLER_FORM_VALIDATECAPTCHA] = true;
			}
		}
		
		return $ret;
	}

	protected function validate_captcha($template_id, $atts) {
	
		return false;
	}
}
