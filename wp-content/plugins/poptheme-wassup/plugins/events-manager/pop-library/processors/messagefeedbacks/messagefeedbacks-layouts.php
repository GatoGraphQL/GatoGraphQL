<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENTS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-events'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PASTEVENTS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-pastevents'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYEVENTS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-myevents'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYPASTEVENTS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-mypastevents'));

class GD_EM_Template_Processor_CustomListMessageFeedbackLayouts extends GD_Template_Processor_ListMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PASTEVENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYEVENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYPASTEVENTS,
		);
	}

	function checkpoint($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYEVENTS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYPASTEVENTS:

				return true;
		}

		return false;
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENTS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYEVENTS:

				$name = __('event', 'poptheme-wassup');
				$names = __('events', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PASTEVENTS:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYPASTEVENTS:

				$name = __('past event', 'poptheme-wassup');
				$names = __('past events', 'poptheme-wassup');
				break;
		}

		$ret['noresults'] = sprintf(
			__('No %s found.', 'poptheme-wassup'),
			$names
		);
		$ret['nomore'] = sprintf(
			__('No more %s found.', 'poptheme-wassup'),
			$names
		);

		if ($this->checkpoint($template_id, $atts)) {

			$ret['checkpoint-error-header'] = __('Login/Register', 'poptheme-wassup');

			// User not yet logged in
			$ret['usernotloggedin'] = sprintf(
				__('Please %s to access your %s.', 'poptheme-wassup'),
				gd_get_login_html(),
				$names
			);

			// User has no access to this functionality (eg: logged in with Facebook)
			$ret['usernoprofileaccess'] = sprintf(
				__('You need a %s account to access this functionality.', 'poptheme-wassup'),
				get_bloginfo('name')
			);

			// User is trying to edit a post which he/she doens't own
			$ret['usercannotedit'] = sprintf(
				__('Your account has no permission to edit this %s.', 'poptheme-wassup'),
				$name
			);
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomListMessageFeedbackLayouts();