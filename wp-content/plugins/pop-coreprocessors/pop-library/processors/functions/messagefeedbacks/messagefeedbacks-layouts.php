<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FOLLOWUSER', PoP_ServerUtils::get_template_definition('layout-messagefeedback-followuser'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNFOLLOWUSER', PoP_ServerUtils::get_template_definition('layout-messagefeedback-unfollowuser'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_RECOMMENDPOST', PoP_ServerUtils::get_template_definition('layout-messagefeedback-recommendpost'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNRECOMMENDPOST', PoP_ServerUtils::get_template_definition('layout-messagefeedback-unrecommendpost'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SUBSCRIBETOTAG', PoP_ServerUtils::get_template_definition('layout-messagefeedback-subscribetotag'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNSUBSCRIBEFROMTAG', PoP_ServerUtils::get_template_definition('layout-messagefeedback-unsubscribefromtag'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UPVOTEPOST', PoP_ServerUtils::get_template_definition('layout-messagefeedback-upvotepost'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNDOUPVOTEPOST', PoP_ServerUtils::get_template_definition('layout-messagefeedback-undoupvotepost'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DOWNVOTEPOST', PoP_ServerUtils::get_template_definition('layout-messagefeedback-downvotepost'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNDODOWNVOTEPOST', PoP_ServerUtils::get_template_definition('layout-messagefeedback-undodownvotepost'));

class GD_Template_Processor_FunctionMessageFeedbackLayouts extends GD_Template_Processor_MessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FOLLOWUSER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNFOLLOWUSER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_RECOMMENDPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNRECOMMENDPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SUBSCRIBETOTAG,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNSUBSCRIBEFROMTAG,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UPVOTEPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNDOUPVOTEPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DOWNVOTEPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNDODOWNVOTEPOST,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		// No headers
		$ret['success-header'] = '';
		$ret['error-header'] = '';
		// $ret['checkpoint-error-header'] = '';

		$towhats = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FOLLOWUSER => __('follow users', 'pop-coreprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNFOLLOWUSER => __('stop following users', 'pop-coreprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_RECOMMENDPOST => __('recommend posts', 'pop-coreprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNRECOMMENDPOST => __('stop recommending posts', 'pop-coreprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SUBSCRIBETOTAG => __('subscribe to tags/topics', 'pop-coreprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNSUBSCRIBEFROMTAG => __('unsubscribe from tags/topics', 'pop-coreprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UPVOTEPOST => __('up-vote posts', 'pop-coreprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNDOUPVOTEPOST => __('stop up-voting posts', 'pop-coreprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DOWNVOTEPOST => __('down-vote posts', 'pop-coreprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNDODOWNVOTEPOST => __('stop down-voting posts', 'pop-coreprocessors'),
		);
		if ($towhat = $towhats[$template_id]) {

			// User not yet logged in
			$ret['usernotloggedin'] = sprintf(
				__('Please %s to %s.', 'pop-coreprocessors'),
				gd_get_login_html(),
				$towhat
			);
		}

		// User has no access to this functionality (eg: logged in with Facebook)
		$ret['usernoprofileaccess'] = sprintf(
			__('You need a %s account to access this functionality.', 'pop-coreprocessors'),
			get_bloginfo('name')
		);

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FunctionMessageFeedbackLayouts();