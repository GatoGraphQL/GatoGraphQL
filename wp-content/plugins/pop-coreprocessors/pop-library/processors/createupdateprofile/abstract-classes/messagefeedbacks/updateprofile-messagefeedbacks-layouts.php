<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UPDATEPROFILE', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-updateprofile'));

class GD_Template_Processor_UpdateProfileMessageFeedbackLayouts extends GD_Template_Processor_UpdateUserFormMesageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UPDATEPROFILE,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UPDATEPROFILE:

				$ret['success-header'] = __('Profile updated successfully.', 'pop-coreprocessors');
				$ret['checkpoint-error-header'] = __('Login', 'pop-coreprocessors');

				// User not yet logged in
				$ret['usernotloggedin'] = sprintf(
					__('Please <a href="%s">log in</a> to edit your Profile.', 'pop-coreprocessors'),
					wp_login_url()
				);
				// User has no access to this functionality (eg: logged in with Facebook)
				$ret['usernoprofileaccess'] = sprintf(
					__('Only %s accounts can be edited.', 'pop-coreprocessors'),
					get_bloginfo('name')
				);

				// Error Messages from URE plug-in
				// Trying to edit profile but it's not an Organization/Individual
				$ret['profilenotorganization'] = __('Your profile is not of \'Organization\' type, so you cannot edit it here.', 'pop-coreprocessors');
				$ret['profilenotindividual'] = __('Your profile is not of \'Individual\' type, so you cannot edit it here.', 'pop-coreprocessors');
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UpdateProfileMessageFeedbackLayouts();