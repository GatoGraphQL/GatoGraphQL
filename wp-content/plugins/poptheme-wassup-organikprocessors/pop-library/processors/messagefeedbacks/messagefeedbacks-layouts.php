<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FARMS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-farms'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYFARMS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-myfarms'));

class OP_Template_Processor_CustomListMessageFeedbackLayouts extends GD_Template_Processor_ListMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FARMS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYFARMS,
		);
	}

	function checkpoint($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYFARMS:

				return true;
		}

		return false;
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FARMS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYFARMS:
			
				$name = __('farm', 'poptheme-wassup-organikprocessors');
				$names = __('farms', 'poptheme-wassup-organikprocessors');
				break;
		}

		$ret['noresults'] = sprintf(
			__('No %s found.', 'poptheme-wassup-organikprocessors'),
			$names
		);
		$ret['nomore'] = sprintf(
			__('No more %s found.', 'poptheme-wassup-organikprocessors'),
			$names
		);

		if ($this->checkpoint($template_id, $atts)) {

			$ret['checkpoint-error-header'] = __('Login/Register', 'poptheme-wassup-organikprocessors');

			// User not yet logged in
			$ret['usernotloggedin'] = sprintf(
				__('Please %s to access your %s.', 'poptheme-wassup-organikprocessors'),
				gd_get_login_html(),
				$names
			);

			// User has no access to this functionality (eg: logged in with Facebook)
			$ret['usernoprofileaccess'] = sprintf(
				__('You need a %s account to access this functionality.', 'poptheme-wassup-organikprocessors'),
				get_bloginfo('name')
			);

			// User is trying to edit a post which he/she doens't own
			$ret['usercannotedit'] = sprintf(
				__('Your account has no permission to edit this %s.', 'poptheme-wassup-organikprocessors'),
				$name
			);

			// The link doesn't contain the nonce
			$ret['nonceinvalid'] = __('Incorrect URL', 'pop-wpapi');
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomListMessageFeedbackLayouts();