<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_InviteUsers extends GD_EmailInvite {

	protected function get_email_content($form_data) {

		$website_html = PoP_EmailTemplates_Factory::get_instance()->get_websitehtml();//PoP_EmailUtils::get_website_html();
		
		// Maybe the user is logged in, maybe not
		if ($sender_name = $form_data['sender-name']) {

			// There might be a sender URL if the user was logged in, or not
			if ($sender_url = $form_data['sender-url']) {
				$sender_html = sprintf('<a href="%s">%s</a>', $sender_url, $sender_name);
			}
			else {
				$sender_html = $sender_name;
			}
			$content = sprintf(
				__( '<p>%s is inviting you to join %s!</p>', 'pop-coreprocessors'),
				$sender_html,
				$website_html
			);
		}
		else {

			$content = sprintf(
				__( '<p>You have been invited to join %s!</p>', 'pop-coreprocessors'),
				$website_html
			);
		}

		// Allow Organik Fundraising to override the content
		$content = apply_filters(
			'GD_InviteUsers:emailcontent',
			$content,
			$sender_html,
			$website_html
		);
		
		// Optional: Additional Message
		if ($additional_msg = $form_data['additional-msg']) {
			$content .= sprintf(
				'<div style="margin-left: 20px;">%s</div>',
				make_clickable(wpautop($additional_msg))
			);		
			$content .= '<br/>';
		}

		$content .= sprintf(
			'<h3>%s</h3>',
			sprintf(
				__('What is %s?', 'pop-coreprocessors'),
				get_bloginfo('name')
			)
		);
		$content .= gd_get_website_description();
		$content .= '<br/><br/>';

		$btn_title = __('Check it out here', 'pop-coreprocessors');
		$content .= PoP_EmailTemplates_Factory::get_instance()->get_buttonhtml($btn_title, get_site_url());

		return $content;
	}

	protected function get_email_subject($form_data) {

		$subject = '';

		// Maybe the user is logged in, maybe not
		if ($sender_name = $form_data['sender-name']) {

			$subject = sprintf( 
				__( '%s is inviting you to join %s!', 'pop-coreprocessors'), 
				$sender_name,
				get_bloginfo('name')
			);
		}
		else {
		
			$subject = sprintf( 
				__( 'You have been invited to join %s!', 'pop-coreprocessors' ), 
				get_bloginfo('name')
			);
		}

		// Allow Organik Fundraising to override the message
		return apply_filters(
			'GD_InviteUsers:emailsubject',
			$subject,
			$sender_name
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_inviteusers;
$gd_inviteusers = new GD_InviteUsers();