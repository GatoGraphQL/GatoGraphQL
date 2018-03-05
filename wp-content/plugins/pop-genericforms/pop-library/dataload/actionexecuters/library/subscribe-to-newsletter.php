<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ActionExecuterInstance_SubscribeToNewsletter {

	protected function validate(&$errors, $form_data) {

		if (empty($form_data['email'])) {
			$errors[] = __('Email cannot be empty.', 'pop-genericforms');
		}
		elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = __('Email format is incorrect.', 'pop-genericforms');
		}
	}

	/**
	 * Function to override
	 */
	protected function additionals($form_data) {

		do_action('pop_subscribetonewsletter', $form_data);
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;

		$form_data = array(
			'email' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL)->get_value(GD_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL, $atts),
			'name' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_NEWSLETTERNAME)->get_value(GD_TEMPLATE_FORMCOMPONENT_NEWSLETTERNAME, $atts),
		);
		
		return $form_data;
	}

	protected function execute($form_data) {

		$to = PoP_EmailSender_Utils::get_admin_notifications_email();		
		$subject = sprintf(
			__('[%s]: %s', 'pop-genericforms'), 
			get_bloginfo('name'),
			__('Newsletter Subscription', 'pop-genericforms')
		);
		$placeholder = '<p><b>%s:</b> %s</p>';
		$msg = sprintf(
			'<p>%s</p>',
			__('User subscribed to newsletter', 'pop-genericforms')
		).sprintf( 
			$placeholder, 
			__('Email', 'pop-genericforms'), 
			sprintf(
				'<a href="mailto:%1$s">%1$s</a>',
				$form_data['email']
			)
		).sprintf( 
			$placeholder, 
			__('Name', 'pop-genericforms'), 
			$form_data['name']
		);

		PoP_EmailSender_Utils::send_email($to, $subject, $msg);
	}

	function subscribe(&$errors, $atts) {

		$form_data = $this->get_form_data($atts);

		$this->validate($errors, $form_data);
		if ($errors) {
			return;
		}

		$result = $this->execute($form_data);
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
