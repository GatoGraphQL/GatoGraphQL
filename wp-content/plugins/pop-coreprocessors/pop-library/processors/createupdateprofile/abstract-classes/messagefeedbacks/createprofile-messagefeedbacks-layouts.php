<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CREATEPROFILE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-createprofile'));

class GD_Template_Processor_CreateProfileMessageFeedbackLayouts extends GD_Template_Processor_CreateUserFormMesageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CREATEPROFILE,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CREATEPROFILE:

				$ret['success-header'] = __('Awesome! Your user account was created successfully!', 'pop-coreprocessors');
				$ret['checkpoint-error-header'] = __('Already logged in!', 'pop-coreprocessors');

				// User already logged in (cannot welcome the person, can't say "Hi Peter!" since this message will be recorded at the beginning, when we still don't have the person logged in)
				$ret['userloggedin'] = sprintf(
					__('Please %s first to create a new user account.', 'pop-coreprocessors'),
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
new GD_Template_Processor_CreateProfileMessageFeedbackLayouts();