<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINK_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-link-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINK_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-link-update'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHT_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-highlight-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHT_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-highlight-update'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOST_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-webpost-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOST_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-webpost-update'));

class Wassup_Template_Processor_CreateUpdatePostFormMessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINK_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINK_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOST_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOST_UPDATE,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		$names = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINK_CREATE => __('Link', 'poptheme-wassup'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINK_UPDATE => __('Link', 'poptheme-wassup'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHT_CREATE => __('Highlight', 'poptheme-wassup'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHT_UPDATE => __('Highlight', 'poptheme-wassup'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOST_CREATE => __('Post', 'poptheme-wassup'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOST_UPDATE => __('Post', 'poptheme-wassup'),
		);
		$creates = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINK_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOST_CREATE,
		);
		$updates = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINK_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOST_UPDATE,
		);

		$name = $names[$template_id];
		$ret['checkpoint-error-header'] = __('Login/Register', 'poptheme-wassup');
		if (in_array($template_id, $creates)) {

			$ret['success-header'] = sprintf(
				__('Yay! Your %s was created successfully!', 'poptheme-wassup'),
				$name
			);
			$ret['update-success-header'] = sprintf(
				__('Yay! Your %s was updated successfully!', 'poptheme-wassup'),
				$name
			);

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
new Wassup_Template_Processor_CreateUpdatePostFormMessageFeedbackLayouts();