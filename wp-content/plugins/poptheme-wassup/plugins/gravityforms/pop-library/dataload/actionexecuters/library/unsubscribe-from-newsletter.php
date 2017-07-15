<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_UnsubscribeFromNewsletter {

	protected function validate(&$errors, $form_data) {

		if (empty($form_data['email'])) {
			$errors[] = __('Email cannot be empty.', 'poptheme-wassup');
		}

		$placeholder_string = __('%s %s', 'poptheme-wassup');
		$makesure_string = __('Please make sure you have clicked on the unsubscription link in the newsletter.', 'poptheme-wassup');
		if (empty($form_data['verificationcode'])) {
			$errors[] = sprintf(
				$placeholder_string,
				__('The verification code is missing.', 'poptheme-wassup'),
				$makesure_string
			);
		}

		if ($errors) {
			return;
		}

		// Verify that the verification code corresponds to the email
		$verificationcode = PoPTheme_Wassup_GF_NewsletterUtils::get_email_verificationcode($form_data['email']);
		if ($verificationcode != $form_data['verificationcode']) {
			$errors[] = sprintf(
				$placeholder_string,
				__('The verification code does not match the email.', 'poptheme-wassup'),
				$makesure_string
			);
		}
	}

	protected function validate_data(&$errors, $newsletter_data) {

		if (empty($newsletter_data['entry-id'])) {
			$errors[] = __('Your email is not subscribed to our newsletter.', 'poptheme-wassup');
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
			'email' => $gd_template_processor_manager->get_processor(GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL)->get_value(GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL, $atts),
			'verificationcode' => $gd_template_processor_manager->get_processor(GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE)->get_value(GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE, $atts),
		);
		
		return $form_data;
	}

	protected function get_newsletter_data($form_data) {

		// Find the entry_id from the email (let's assume there is only one. If there is more than one, that is the user subscribed more than once, so will have to unsubscribe more than once. HOhohoho)
		$entry_id = $form_data['email'];
		$search_criteria = array(
			'status' => 'active',
			'field_filters' => array(
				array(
					'key' => '1'/*POPTHEME_WASSUP_GF_NEWSLETTER_FIELDNAME_EMAIL_ID*/,
					'value' => $form_data['email'],
				),
			),
		);
		$entries = GFAPI::get_entries(GD_Template_Helper_GFForm::get_newsletter_form_id(), $search_criteria);
		if (!$entries) {
			return array();
		}
		$entry = $entries[0];
		return array(
			'entry-id' => $entries[0]['id'],
		);
	}

	protected function execute($newsletter_data) {

		return GFAPI::delete_entry($newsletter_data['entry-id']);
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

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// global $pop_unsubscribe_from_newsletter;
// $pop_unsubscribe_from_newsletter = new PoP_UnsubscribeFromNewsletter();