<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_CONTACTUS', PoP_TemplateIDUtils::get_template_definition('action-contactus'));
define ('GD_TEMPLATE_ACTION_CONTACTUSER', PoP_TemplateIDUtils::get_template_definition('action-contactuser'));
define ('GD_TEMPLATE_ACTION_SHAREBYEMAIL', PoP_TemplateIDUtils::get_template_definition('action-sharebyemail'));
define ('GD_TEMPLATE_ACTION_VOLUNTEER', PoP_TemplateIDUtils::get_template_definition('action-volunteer'));
define ('GD_TEMPLATE_ACTION_FLAG', PoP_TemplateIDUtils::get_template_definition('action-flag'));
define ('GD_TEMPLATE_ACTION_NEWSLETTER', PoP_TemplateIDUtils::get_template_definition('action-newsletter'));
define ('GD_TEMPLATE_ACTION_NEWSLETTERUNSUBSCRIPTION', PoP_TemplateIDUtils::get_template_definition('action-newsletterunsubscription'));

class GenericForms_Template_Processor_Actions extends GD_Template_Processor_FormActionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ACTION_CONTACTUS,
			GD_TEMPLATE_ACTION_CONTACTUSER,
			GD_TEMPLATE_ACTION_SHAREBYEMAIL,
			GD_TEMPLATE_ACTION_VOLUNTEER,
			GD_TEMPLATE_ACTION_FLAG,
			GD_TEMPLATE_ACTION_NEWSLETTER,
			GD_TEMPLATE_ACTION_NEWSLETTERUNSUBSCRIPTION,
		);
	}
	
	// // Comment Leo 12/01/2017: make it runtime instead of static, since it needs to validate if the user is logged in
	// // function get_data_setting($template_id, $atts) {
	// function get_runtime_datasetting($template_id, $atts) {

	// 	// $ret = parent::get_data_setting($template_id, $atts);
	// 	$ret = parent::get_runtime_datasetting($template_id, $atts);
	
	// 	switch ($template_id) {
				
	// 		case GD_TEMPLATE_ACTION_CONTACTUS:
	// 		case GD_TEMPLATE_ACTION_CONTACTUSER:
	// 		case GD_TEMPLATE_ACTION_SHAREBYEMAIL:
	// 		case GD_TEMPLATE_ACTION_VOLUNTEER:
	// 		case GD_TEMPLATE_ACTION_FLAG:

	// 			// Check if needed to validate Captcha
	// 			$vars = GD_TemplateManager_Utils::get_vars();
	// 			if (!(PoP_FormUtils::use_loggedinuser_data() && $vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/)) {
	// 				$ret['iohandler-atts'][GD_DATALOAD_IOHANDLER_FORM_VALIDATECAPTCHA] = true;
	// 			}
	// 			break;
	// 	}
		
	// 	return $ret;
	// }
	function validate_captcha($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_ACTION_CONTACTUS:
			case GD_TEMPLATE_ACTION_CONTACTUSER:
			case GD_TEMPLATE_ACTION_SHAREBYEMAIL:
			case GD_TEMPLATE_ACTION_VOLUNTEER:
			case GD_TEMPLATE_ACTION_FLAG:

				return true;
		}
		
		return parent::validate_captcha($template_id, $atts);
	}

	protected function get_actionexecuter($template_id) {
	
		$actionexecuters = array(
			GD_TEMPLATE_ACTION_CONTACTUS => GD_DATALOAD_ACTIONEXECUTER_CONTACTUS,
			GD_TEMPLATE_ACTION_CONTACTUSER => GD_DATALOAD_ACTIONEXECUTER_CONTACTUSER,
			GD_TEMPLATE_ACTION_SHAREBYEMAIL => GD_DATALOAD_ACTIONEXECUTER_SHAREBYEMAIL,
			GD_TEMPLATE_ACTION_VOLUNTEER => GD_DATALOAD_ACTIONEXECUTER_VOLUNTEER,
			GD_TEMPLATE_ACTION_FLAG => GD_DATALOAD_ACTIONEXECUTER_FLAG,
			GD_TEMPLATE_ACTION_NEWSLETTER => GD_DATALOAD_ACTIONEXECUTER_NEWSLETTERSUBSCRIPTION,
			GD_TEMPLATE_ACTION_NEWSLETTERUNSUBSCRIPTION => GD_DATALOAD_ACTIONEXECUTER_NEWSLETTERUNSUBSCRIPTION,
		);
		if ($actionexecuter = $actionexecuters[$template_id]) {

			// Allow Gravity Forms to replace it
			return apply_filters(
				'GenericForms_Template_Processor_Actions:action-executer',
				$actionexecuter,
				$template_id
			);
		}

		return parent::get_actionexecuter($template_id);
	}

	protected function get_iohandler($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_CONTACTUS:
			case GD_TEMPLATE_ACTION_CONTACTUSER:
			case GD_TEMPLATE_ACTION_SHAREBYEMAIL:
			case GD_TEMPLATE_ACTION_VOLUNTEER:
			case GD_TEMPLATE_ACTION_FLAG:
			case GD_TEMPLATE_ACTION_NEWSLETTER:
			case GD_TEMPLATE_ACTION_NEWSLETTERUNSUBSCRIPTION:

				// Allow Gravity Forms to replace it
				return apply_filters(
					'GenericForms_Template_Processor_Actions:iohandler',
					GD_DATALOAD_IOHANDLER_FORM,
					$template_id
				);
		}

		return parent::get_iohandler($template_id);
	}

	function get_settings_id($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_CONTACTUS:

				return GD_TEMPLATE_BLOCK_CONTACTUS;

			case GD_TEMPLATE_ACTION_CONTACTUSER:

				return GD_TEMPLATE_BLOCK_CONTACTUSER;

			case GD_TEMPLATE_ACTION_SHAREBYEMAIL:

				return GD_TEMPLATE_BLOCK_SHAREBYEMAIL;

			case GD_TEMPLATE_ACTION_VOLUNTEER:

				return GD_TEMPLATE_BLOCK_VOLUNTEER;

			case GD_TEMPLATE_ACTION_FLAG:

				return GD_TEMPLATE_BLOCK_FLAG;

			case GD_TEMPLATE_ACTION_NEWSLETTER:

				return GD_TEMPLATE_BLOCK_NEWSLETTER;
		
			case GD_TEMPLATE_ACTION_NEWSLETTERUNSUBSCRIPTION:

				return GD_TEMPLATE_BLOCK_NEWSLETTERUNSUBSCRIPTION;
		}

		return parent::get_settings_id($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GenericForms_Template_Processor_Actions();