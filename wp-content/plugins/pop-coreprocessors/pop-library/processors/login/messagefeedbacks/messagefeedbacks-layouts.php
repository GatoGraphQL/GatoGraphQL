<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOGIN', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-login'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOSTPWD', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-lostpwd'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOSTPWDRESET', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-lostpwdreset'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOGOUT', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-logout'));

class GD_Template_Processor_LoginMessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOGIN,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOSTPWD,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOSTPWDRESET,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOGOUT,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		$ret['error-header'] = __('Still not there...', 'pop-coreprocessors');
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOGIN:

				$ret['success-header'] = __('Hurray, login successful!', 'pop-coreprocessors');
				$addnew = '<i class="fa fa-fw fa-plus"></i>'.__('Add', 'pop-coreprocessors');
				$ret['success'] = __('You can now add posts and comments, follow users, etc', 'pop-coreprocessors');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOSTPWD:

				$ret['success-header'] = __('Almost there...', 'pop-coreprocessors');
				$ret['success'] = sprintf(
					__('We sent you an email with a code. Please copy it and <a href="%s">paste it here</a>.', 'pop-coreprocessors'),
					get_permalink(POP_WPAPI_PAGE_LOSTPWDRESET)
				);
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOSTPWDRESET:

				$ret['success-header'] = __('Password reset successful', 'pop-coreprocessors');
				$ret['success'] = sprintf(
					__('Please <a href="%s">click here to log in</a>.', 'pop-coreprocessors'),
					wp_login_url()
				);
				$ret['error-nocode'] = __('The code is missing', 'pop-coreprocessors');
				$ret['error-wrongcode'] = __('The code is not correct', 'pop-coreprocessors');
				$ret['error-nopwd'] = __('Password is missing', 'pop-coreprocessors');
				$ret['error-short'] = __('The password must be at least 8 characters long.', 'pop-coreprocessors');
				$ret['error-norepeatpwd'] = __('Repeat password is missing', 'pop-coreprocessors');
				$ret['error-pwdnomatch'] = __('The passwords do not match', 'pop-coreprocessors');
				$ret['error-invalidkey'] = sprintf(
					__('The code is invalid or expired. Please <a href="%s">generate it again</a>.', 'pop-coreprocessors'),
					wp_lostpassword_url()
				);
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOGOUT:

				$ret['success-header'] = __('Logged out now', 'pop-coreprocessors');
				$ret['success'] = __('But please make sure to come back!', 'pop-coreprocessors');
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOSTPWD:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOSTPWDRESET:

				// User already logged in (cannot welcome the person, can't say "Hi Peter!" since this message will be recorded at the beginning, when we still don't have the person logged in)
				$ret['userloggedin'] = sprintf(
					__('You are already logged in, please %s first to re-set your password.', 'pop-coreprocessors'),
					sprintf(
						'<a href="%s">logout</a>',
						wp_logout_url()
					)
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LoginMessageFeedbackLayouts();