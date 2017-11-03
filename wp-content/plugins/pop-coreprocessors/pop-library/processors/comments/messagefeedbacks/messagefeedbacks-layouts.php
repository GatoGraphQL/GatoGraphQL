<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_COMMENTS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-comments'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ADDCOMMENT', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-addcomment'));

class GD_Template_Processor_CommentsMessageFeedbackLayouts extends GD_Template_Processor_MessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_COMMENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ADDCOMMENT,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_COMMENTS:

				$names = __('comments', 'pop-coreprocessors');
				$ret['noresults'] = sprintf(
					__('No %s yet.', 'pop-coreprocessors'),
					$names
				);
				$ret['nomore'] = sprintf(
					__('That\'s it, no more %s found.', 'pop-coreprocessors'),
					$names
				);
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ADDCOMMENT:

				$ret['error-header'] = __('Ops, there were some problems:', 'pop-coreprocessors');
				$ret['success-header'] = __('Comment added successfully!', 'pop-coreprocessors');
				$ret['empty-content'] = __('Comment is missing.', 'pop-coreprocessors');
				$ret['success'] = __('Your comment was added.', 'pop-coreprocessors');
				$ret['checkpoint-error-header'] = __('Login/Register', 'pop-coreprocessors');
				$ret['usernotloggedin'] = sprintf(
				__('Please %s to add a comment.', 'pop-coreprocessors'),
					gd_get_login_html()
				);
				// User has no access to this functionality (eg: logged in with Facebook)
				$ret['usernoprofileaccess'] = sprintf(
					__('You need a %s account to add a comment.', 'pop-coreprocessors'),
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
new GD_Template_Processor_CommentsMessageFeedbackLayouts();