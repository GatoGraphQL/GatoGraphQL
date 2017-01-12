<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTE_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-opinionatedvote-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTE_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-opinionatedvote-update'));

class VotingProcessors_Template_Processor_CreateUpdatePostFormMessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTE_UPDATE,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		$title = gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES);//__('Thought', 'poptheme-wassup-votingprocessors');
		$names = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTE_CREATE => $title,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTE_UPDATE => $title,
		);
		$creates = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTE_CREATE,
		);
		$updates = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTE_UPDATE,
		);

		$name = $names[$template_id];
		$ret['checkpoint-error-header'] = __('Login/Register', 'poptheme-wassup-votingprocessors');
		if (in_array($template_id, $creates)) {

			$ret['success-header'] = sprintf(
				__('Yay! Your %s was created successfully!', 'poptheme-wassup-votingprocessors'),
				$name
			);
			$ret['update-success-header'] = sprintf(
				__('Yay! Your %s was updated successfully!', 'poptheme-wassup-votingprocessors'),
				$name
			);

			// User not yet logged in
			$ret['usernotloggedin'] = sprintf(
				__('Please %s to create your %s.', 'poptheme-wassup-votingprocessors'),
				gd_get_login_html(),
				$name
			);
			// User has no access to this functionality (eg: logged in with Facebook)
			$ret['usernoprofileaccess'] = sprintf(
				__('You need a %s account to create your %s.', 'poptheme-wassup-votingprocessors'),
				get_bloginfo('name'),
				$name
			);
		}
		elseif (in_array($template_id, $updates)) {

			$ret['success-header'] = sprintf(
				__('%s updated successfully.', 'poptheme-wassup-votingprocessors'),
				$name
			);

			// User not yet logged in
			$ret['usernotloggedin'] = sprintf(
				__('Please <a href="%s">log in</a> to edit your %s.', 'poptheme-wassup-votingprocessors'),
				wp_login_url(),
				$name
			);
			// User has no access to this functionality (eg: logged in with Facebook)
			$ret['usernoprofileaccess'] = sprintf(
				__('You need a %s account to edit your %s.', 'poptheme-wassup-votingprocessors'),
				get_bloginfo('name'),
				$name
			);

			// User has no access to this post (eg: editing someone else's post)
			$ret['usercannotedit'] = sprintf(
				__('Ops, it seems you have no rights to edit this %s.', 'poptheme-wassup-votingprocessors'),
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
new VotingProcessors_Template_Processor_CreateUpdatePostFormMessageFeedbackLayouts();