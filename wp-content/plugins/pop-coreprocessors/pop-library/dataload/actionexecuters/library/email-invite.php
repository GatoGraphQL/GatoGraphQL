<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_EmailInvite {

	function execute(&$errors, $atts) {

		$form_data = $this->get_form_data($atts);

		$this->validate($errors, $form_data);

		// No need to validate for errors, because the email will be sent to all valid emails anyway,
		// so sending might fail for some emails but not others, and we give a message to the user about these
		// if ($errors) {
		// 	return;
		// }

		return $this->send_invite($errors, $form_data);
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;		

		// Get the list of all emails
		$emails = array();
		$form_emails = trim($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_EMAILS)->get_value(GD_TEMPLATE_FORMCOMPONENT_EMAILS, $atts));
		// Remove newlines
		$form_emails = trim(preg_replace('/\s+/', ' ', $form_emails));
		if ($form_emails) {
			
			foreach (multiexplode(array(',', ';', ' '), $form_emails) as $email) {

				// Remove white spaces
				$email = trim($email);
				if ($email) {
					$emails[] = $email;
				}
			}
		}

		if (PoP_FormUtils::use_loggedinuser_data() && is_user_logged_in()) {

			$user_id = get_current_user_id();
			$sender_name = get_the_author_meta('display_name', $user_id);
			$sender_url = get_author_posts_url($user_id);
		}
		else {

			$sender_name = trim($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_SENDERNAME)->get_value(GD_TEMPLATE_FORMCOMPONENT_SENDERNAME, $atts));
			$captcha = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CAPTCHA)->get_value(GD_TEMPLATE_FORMCOMPONENT_CAPTCHA, $atts);
			$user_id = $sender_url = '';
		}
		$additional_msg = trim($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE)->get_value(GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE, $atts));
		$form_data = array(
			'emails' => $emails,
			'user_id' => $user_id,
			'sender-name' => $sender_name,
			'sender-url' => $sender_url,
			'additional-msg' => $additional_msg,
			'captcha' => $captcha,
		);
		
		return $form_data;
	}	

	protected function validate(&$errors, &$form_data) {
		
		$emails = $form_data['emails'];
		$invalid_emails = array();
		foreach ($emails as $email) {

			if (!is_email($email)) {

				$invalid_emails[] = $email;
			}
		}
		if (!empty($invalid_emails)) {
			$errors[] = sprintf(
				__('The following emails are invalid: <strong>%s</strong>', 'pop-coreprocessors'),
				implode(', ', $invalid_emails)
			);
		}

		if (empty($emails)) {
			$errors[] = __('Email(s) cannot be empty.', 'pop-coreprocessors');
		}

		// Re-assign the non-invalid emails to the form_data
		$form_data['emails'] = array_diff($emails, $invalid_emails);

		// Validate the captcha
		if (!PoP_FormUtils::use_loggedinuser_data() || !is_user_logged_in()) {
			
			$captcha = $form_data['captcha'];
			
			$captcha_validation = GD_Captcha::validate($captcha['input'], $captcha['session']);
			if (is_wp_error($captcha_validation)) {
				$errors[] = $captcha_validation->get_error_message();
			}
		}
	}

	/** Function to override */
	protected function get_email_content($form_data) {

		return '';
	}
	/** Function to override */
	protected function get_email_subject($form_data) {

		return '';
	}

	protected function send_invite(&$errors, $form_data) {

		$emails = $form_data['emails'];
		if (!empty($emails)) {

			$subject = $this->get_email_subject($form_data);
			$content = $this->get_email_content($form_data);
			PoP_EmailSender_Utils::sendemail_to_users($emails, array(), $subject, $content, true);
		}

		return $emails;
	}
}
