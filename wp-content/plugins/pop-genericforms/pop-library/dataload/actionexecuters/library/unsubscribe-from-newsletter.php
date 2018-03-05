<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_UnsubscribeFromNewsletter {

	protected function validate(&$errors, $form_data) {

		if (empty($form_data['email'])) {
			$errors[] = __('Email cannot be empty.', 'pop-genericforms');
		}
		elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = __('Email format is incorrect.', 'pop-genericforms');
		}

		$placeholder_string = __('%s %s', 'pop-genericforms');
		$makesure_string = __('Please make sure you have clicked on the unsubscription link in the newsletter.', 'pop-genericforms');
		if (empty($form_data['verificationcode'])) {
			$errors[] = sprintf(
				$placeholder_string,
				__('The verification code is missing.', 'pop-genericforms'),
				$makesure_string
			);
		}

		if ($errors) {
			return;
		}

		// Verify that the verification code corresponds to the email
		$verificationcode = PoP_GenericForms_NewsletterUtils::get_email_verificationcode($form_data['email']);
		if ($verificationcode != $form_data['verificationcode']) {
			$errors[] = sprintf(
				$placeholder_string,
				__('The verification code does not match the email.', 'pop-genericforms'),
				$makesure_string
			);
		}
	}

	/**
	 * Function to override
	 */
	protected function additionals($form_data) {

		do_action('pop_unsubscribe_from_newsletter', $form_data);
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;

		$form_data = array(
			'email' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL)->get_value(GD_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL, $atts),
			'verificationcode' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE)->get_value(GD_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE, $atts),
		);
		
		return $form_data;
	}

	/**
	 * Function to override by Gravity Forms
	 */
	protected function get_newsletter_data($form_data) {

		return array();
	}
	/**
	 * Function to override by Gravity Forms
	 */
	protected function validate_data(&$errors, $newsletter_data) {

	}

	protected function execute($newsletter_data) {

		$to = PoP_EmailSender_Utils::get_admin_notifications_email();		
		$subject = sprintf(
			__('[%s]: Newsletter unsubscription', 'pop-genericforms'), 
			get_bloginfo('name')
		);
		$placeholder = '<p><b>%s:</b> %s</p>';
		$msg = sprintf(
			'<p>%s</p>',
			__('User unsubscribed from newsletter', 'pop-genericforms')
		).sprintf( 
			$placeholder, 
			__('Email', 'pop-genericforms'), 
			$newsletter_data['email']
		);

		PoP_EmailSender_Utils::send_email($to, $subject, $msg);
		// return GFAPI::delete_entry($newsletter_data['entry-id']);
	}

	function unsubscribe(&$errors, $atts) {

		$form_data = $this->get_form_data($atts);

		$this->validate($errors, $form_data);
		if ($errors) {
			return;
		}

		$newsletter_data = $this->get_newsletter_data($form_data);
		$this->validate_data($errors, $newsletter_data);
		if ($errors) {
			return;
		}

		$result = $this->execute($newsletter_data);
		if (is_wp_error($result)) {

			foreach ($result->get_error_messages() as $error_msg) {
				$errors[] = $error_msg;
			}
			return;
		}

		// Allow for additional operations
		$this->additionals($form_data);

		return $result;
	}
}
