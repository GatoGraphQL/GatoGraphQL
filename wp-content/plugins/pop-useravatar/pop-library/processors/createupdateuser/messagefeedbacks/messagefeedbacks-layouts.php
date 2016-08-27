<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USERAVATAR_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-useravatar-update'));

class PoP_UserAvatar_Template_Processor_UserMessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USERAVATAR_UPDATE,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USERAVATAR_UPDATE:

				$ret['success-header'] = __('Picture updated successfully.', 'pop-useravatar');
				$ret['success'] = __('Such a good shot!', 'pop-useravatar');

				$ret['checkpoint-error-header'] = __('Login', 'pop-useravatar');

				// User not yet logged in
				$ret['usernotloggedin'] = sprintf(
					__('Please <a href="%s">log in</a> to update your picture.', 'pop-useravatar'),
					wp_login_url()
				);
				// User has no access to this functionality (eg: logged in with Facebook)
				$ret['usernoprofileaccess'] = sprintf(
					__('Only %s accounts can update their picture.', 'pop-useravatar'),
					get_bloginfo('name')
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_UserAvatar_Template_Processor_UserMessageFeedbackLayouts();