<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENT_CREATE', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-event-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENT_UPDATE', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-event-update'));

class GD_EM_Template_Processor_CreateUpdatePostFormMessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENT_UPDATE
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		$names = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENT_CREATE => __('Event', 'poptheme-wassup'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENT_UPDATE => __('Event', 'poptheme-wassup')
		);
		$creates = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENT_CREATE,
		);
		$updates = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EVENT_UPDATE,
		);

		$name = $names[$template_id];
		$ret['checkpoint-error-header'] = __('Login/Register', 'poptheme-wassup');
		if (in_array($template_id, $creates)) {

			$ret['success-header'] = sprintf(
				// __('Yay! Your %s was created/updated successfully!', 'poptheme-wassup'),
				__('Yay! Your %s was created successfully!', 'poptheme-wassup'),
				$name
			);
			$ret['update-success-header'] = sprintf(
				__('Yay! Your %s was updated successfully!', 'poptheme-wassup'),
				$name
			);
			// $ret['success-footer'] = sprintf(
			// 	__('<hr/><div class="text-center"><a href="#" class="pop-reset"><span class="glyphicon glyphicon-refresh"></span> Click here to Reset / Create a new %s</a></div>', 'poptheme-wassup'),
			// 	$name
			// );

			// User not yet logged in
			$ret['usernotloggedin'] = sprintf(
				__('Please %s to create your %s.', 'poptheme-wassup'),
				gd_get_login_html(),
				$name
			);
			// User has no access to this functionality (eg: logged in with Facebook)
			$ret['usernoprofileaccess'] = sprintf(
				__('You need a %s account to create your %s.', 'poptheme-wassup'),
				get_bloginfo('name'),
				$name
			);
		}
		elseif (in_array($template_id, $updates)) {

			$ret['success-header'] = sprintf(
				__('%s updated successfully.', 'poptheme-wassup'),
				$name
			);

			// User not yet logged in
			$ret['usernotloggedin'] = sprintf(
				__('Please <a href="%s">log in</a> to edit your %s.', 'poptheme-wassup'),
				wp_login_url(),
				$name
			);
			// User has no access to this functionality (eg: logged in with Facebook)
			$ret['usernoprofileaccess'] = sprintf(
				__('You need a %s account to edit your %s.', 'poptheme-wassup'),
				get_bloginfo('name'),
				$name
			);

			// User has no access to this post (eg: editing someone else's post)
			$ret['usercannotedit'] = sprintf(
				__('Ops, it seems you have no rights to edit this %s.', 'poptheme-wassup'),
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
new GD_EM_Template_Processor_CreateUpdatePostFormMessageFeedbackLayouts();