<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ActionExecuterInstance_ContactUser {

	protected function validate(&$errors, $form_data) {

		if (empty($form_data['name'])) {
			$errors[] = __('Your name cannot be empty.', 'pop-genericforms');
		}

		if (empty($form_data['email'])) {
			$errors[] = __('Email cannot be empty.', 'pop-genericforms');
		}
		elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = __('Email format is incorrect.', 'pop-genericforms');
		}

		if (empty($form_data['message'])) {
			$errors[] = __('Message cannot be empty.', 'pop-genericforms');
		}

		if (empty($form_data['target-id'])) {

			$errors[] = __('The requested user cannot be empty.', 'pop-genericforms');
		}
		else {

			$target = get_user_by('id', $form_data['target-id']);
			if (!$target) {

				$errors[] = __('The requested user does not exist.', 'pop-genericforms');
			}
		}
	}

	/**
	 * Function to override
	 */
	protected function additionals($form_data) {

		do_action('pop_contactuser', $form_data);
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;

		$form_data = array(
			'name' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_NAME)->get_value(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts),
			'email' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_EMAIL)->get_value(GD_TEMPLATE_FORMCOMPONENT_EMAIL, $atts),
			'subject' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_SUBJECT)->get_value(GD_TEMPLATE_FORMCOMPONENT_SUBJECT, $atts),
			'message' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_MESSAGE)->get_value(GD_TEMPLATE_FORMCOMPONENT_MESSAGE, $atts),
			'target-id' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_USERID)->get_value(GD_TEMPLATE_FORMCOMPONENT_USERID, $atts),
		);
		
		return $form_data;
	}

	protected function execute($form_data) {

		$websitename = get_bloginfo('name');
		$subject = sprintf(
			__('[%s]: %s', 'pop-genericforms'), 
			$websitename,
			$form_data['subject'] ? $form_data['subject'] : sprintf(
				__('%s sends you a message', 'pop-genericforms'),
				$form_data['name']
			)
		);
		$placeholder = '<p><b>%s:</b> %s</p>';
		$msg = sprintf(
			'<p>%s</p>',
			sprintf(
				__('You have been sent a message from a user in %s', 'pop-genericforms'),
				$websitename
			)
		).sprintf( 
			$placeholder, 
			__('Name', 'pop-genericforms'), 
			$form_data['name']
		).sprintf( 
			$placeholder, 
			__('Email', 'pop-genericforms'), 
			sprintf(
				'<a href="mailto:%1$s">%1$s</a>',
				$form_data['email']
			)
		).sprintf( 
			$placeholder, 
			__('Subject', 'pop-genericforms'), 
			$form_data['subject']
		).sprintf( 
			$placeholder, 
			__('Message', 'pop-genericforms'), 
			$form_data['message']
		);

		PoP_EmailSender_Utils::sendemail_to_user($form_data['target-id'], $subject, $msg);
	}

	function contactuser(&$errors, $atts) {

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
