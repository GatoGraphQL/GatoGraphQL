<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USER_CHANGEPASSWORD', PoP_ServerUtils::get_template_definition('layout-messagefeedback-user-changepassword'));

class GD_Template_Processor_UserMessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USER_CHANGEPASSWORD,
			// GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USERAVATAR_UPDATE,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USER_CHANGEPASSWORD:

				$ret['success-header'] = __('Password updated successfully.', 'pop-coreprocessors');
				$ret['success'] = __('Yep, it\'s good to change it every now and then, with so many crooks around!', 'pop-coreprocessors');

				$ret['checkpoint-error-header'] = __('Ops, there is a problem:', 'pop-coreprocessors');

				// User not yet logged in
				$ret['usernotloggedin'] = sprintf(
					__('Please <a href="%s">log in</a> to change your password.', 'pop-coreprocessors'),
					wp_login_url()
				);
				// User has no access to this functionality (eg: logged in with Facebook)
				$ret['usernoprofileaccess'] = sprintf(
					__('Only %s accounts can change their password.', 'pop-coreprocessors'),
					get_bloginfo('name')
				);
				// Not available to WSL users
				$ret['wsluser'] = sprintf(
					__('Only %s accounts can change their password.', 'pop-coreprocessors'),
					get_bloginfo('name')
				);
				break;

			// case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USERAVATAR_UPDATE:

			// 	$ret['success-header'] = __('Picture updated successfully.', 'pop-coreprocessors');
			// 	$ret['success'] = __('Such a good shot!', 'pop-coreprocessors');

			// 	$ret['checkpoint-error-header'] = __('Login', 'pop-coreprocessors');

			// 	// User not yet logged in
			// 	$ret['usernotloggedin'] = sprintf(
			// 		__('Please <a href="%s">log in</a> to update your picture.', 'pop-coreprocessors'),
			// 		wp_login_url()
			// 	);
			// 	// User has no access to this functionality (eg: logged in with Facebook)
			// 	$ret['usernoprofileaccess'] = sprintf(
			// 		__('Only %s accounts can update their picture.', 'pop-coreprocessors'),
			// 		get_bloginfo('name')
			// 	);
			// 	break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserMessageFeedbackLayouts();