<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('layout-messagefeedback-opinionatedvotes'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('layout-messagefeedback-myopinionatedvotes'));

class VotingProcessors_Template_Processor_CustomListMessageFeedbackLayouts extends GD_Template_Processor_ListMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYOPINIONATEDVOTES,
		);
	}

	function checkpoint($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYOPINIONATEDVOTES:

				return true;
		}

		return false;
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTES:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYOPINIONATEDVOTES:
			
				// $name = __('thought', 'poptheme-wassup-votingprocessors');
				// $names = __('thoughts', 'poptheme-wassup-votingprocessors');
				$name = gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES, 'lc');
				$names = gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES, 'plural-lc');
				break;
		}

		$ret['noresults'] = sprintf(
			__('No %s found.', 'poptheme-wassup-votingprocessors'),
			$names
		);
		$ret['nomore'] = sprintf(
			__('No more %s found.', 'poptheme-wassup-votingprocessors'),
			$names
		);

		if ($this->checkpoint($template_id, $atts)) {

			$ret['checkpoint-error-header'] = __('Login/Register', 'poptheme-wassup-votingprocessors');

			// User not yet logged in
			$ret['usernotloggedin'] = sprintf(
				__('Please %s to access your %s.', 'poptheme-wassup-votingprocessors'),
				gd_get_login_html(),
				$names
			);

			// User has no access to this functionality (eg: logged in with Facebook)
			$ret['usernoprofileaccess'] = sprintf(
				__('You need a %s account to access this functionality.', 'poptheme-wassup-votingprocessors'),
				get_bloginfo('name')
			);

			// User is trying to edit a post which he/she doens't own
			$ret['usercannotedit'] = sprintf(
				__('Your account has no permission to edit this %s.', 'poptheme-wassup-votingprocessors'),
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
new VotingProcessors_Template_Processor_CustomListMessageFeedbackLayouts();