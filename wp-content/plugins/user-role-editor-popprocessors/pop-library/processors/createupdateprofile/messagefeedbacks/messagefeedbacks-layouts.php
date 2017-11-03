<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCOMMUNITIES', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-mycommunities'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INVITENEWMEMBERS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-invitemembers'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EDITMEMBERSHIP', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-editmembership'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYMEMBERS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-mymembers'));

class GD_URE_Template_Processor_ProfileMessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCOMMUNITIES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INVITENEWMEMBERS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EDITMEMBERSHIP,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYMEMBERS,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCOMMUNITIES:
			
				$ret['success-header'] = __('Update successful!', 'ure-popprocessors');
				$ret['success'] = __('Your changes have been saved.', 'ure-popprocessors');

				$ret['checkpoint-error-header'] = __('User account', 'ure-popprocessors');

				// User not yet logged in
				$ret['usernotloggedin'] = sprintf(
					__('Please %s to edit your Organizations.', 'ure-popprocessors'),
					gd_get_login_html()
				);
				// User has no access to this functionality (eg: logged in with Facebook)
				$ret['usernoprofileaccess'] = sprintf(
					__('Only %s accounts can add Organizations\' membership.', 'ure-popprocessors'),
					get_bloginfo('name')
				);
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INVITENEWMEMBERS:
			
				$ret['success-header'] = __('Invite successful!', 'ure-popprocessors');
				$ret['checkpoint-error-header'] = __('User account', 'ure-popprocessors');

				// User not yet logged in
				$ret['usernotloggedin'] = sprintf(
					__('Please %s to invite your members.', 'ure-popprocessors'),
					gd_get_login_html()
				);
				// User has no access to this functionality (eg: logged in with Facebook)
				$ret['usernoprofileaccess'] = sprintf(
					__('Only %s accounts can invite members.', 'ure-popprocessors'),
					get_bloginfo('name')
				);
				// Only Organizations can invite members
				$ret['profilenotorganization'] = __('Only Organizations can invite members.', 'ure-popprocessors');
				$ret['profilenotcommunity'] = __('Your Organization is configured to not accept members. Please edit your user account to change this setting.', 'ure-popprocessors');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EDITMEMBERSHIP:
			
				$ret['success-header'] = __('Status update successful!', 'ure-popprocessors');
				$ret['success'] = __('Your changes have been saved.', 'ure-popprocessors');
				$ret['checkpoint-error-header'] = __('User account', 'ure-popprocessors');

				// User not yet logged in
				$ret['usernotloggedin'] = sprintf(
					__('Please <a href="%s">log in</a> to update your members\' information.', 'ure-popprocessors'),
					wp_login_url()
				);
				// User has no access to this functionality (eg: logged in with Facebook)
				$ret['usernoprofileaccess'] = sprintf(
					__('Only %s accounts can edit their members\' information.', 'ure-popprocessors'),
					get_bloginfo('name')
				);
				// Only Organizations can invite members
				$ret['profilenotorganization'] = __('Only Organizations can have members.', 'ure-popprocessors');
				$ret['profilenotcommunity'] = __('Your Organization is configured to not accept members. Please edit your user account to change this setting.', 'ure-popprocessors');
				$ret['editingnotcommunitymember'] = __('This user is not a member of your Organization.', 'ure-popprocessors');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYMEMBERS:
			
				$ret['noresults'] = __('No members found.', 'ure-popprocessors');
				$ret['nomore'] = __('No more members found.', 'ure-popprocessors');
				$ret['checkpoint-error-header'] = __('User account', 'ure-popprocessors');

				// User not yet logged in
				$ret['usernotloggedin'] = sprintf(
					__('Please %s to see your members.', 'ure-popprocessors'),
					gd_get_login_html()
				);
				// User has no access to this functionality (eg: logged in with Facebook)
				$ret['usernoprofileaccess'] = sprintf(
					__('Only %s accounts can have members.', 'ure-popprocessors'),
					get_bloginfo('name')
				);
				// Only Organizations can invite members
				$ret['profilenotorganization'] = __('Only Organizations can have members.', 'ure-popprocessors');
				$ret['profilenotcommunity'] = __('Your Organization is configured to not accept members. Please edit your user account to change this setting.', 'ure-popprocessors');
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_ProfileMessageFeedbackLayouts();