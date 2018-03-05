<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ActionExecuterInstance_ShareByEmail {

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

		if (empty($form_data['target-url'])) {
			$errors[] = __('The shared-page URL cannot be empty.', 'pop-genericforms');
		}

		if (empty($form_data['target-title'])) {
			$errors[] = __('The shared-page title cannot be empty.', 'pop-genericforms');
		}
	}

	/**
	 * Function to override
	 */
	protected function additionals($form_data) {

		do_action('pop_sharebyemail', $form_data);
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;

		$form_data = array(
			'name' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_NAME)->get_value(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts),
			'email' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_DESTINATIONEMAIL)->get_value(GD_TEMPLATE_FORMCOMPONENT_DESTINATIONEMAIL, $atts),
			'message' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE)->get_value(GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE, $atts),
			'target-url' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_TARGETURL)->get_value(GD_TEMPLATE_FORMCOMPONENT_TARGETURL, $atts),
			'target-title' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE)->get_value(GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE, $atts),
		);
		
		return $form_data;
	}

	protected function execute($form_data) {

		$subject = sprintf(
			__('[%s]: %s', 'pop-genericforms'), 
			get_bloginfo('name'),
			sprintf(
				__('%s is sharing with you: %s', 'pop-genericforms'), 
				$form_data['name'],
				$form_data['target-title']
			)
		);
		$placeholder = '<p><b>%s:</b> %s</p>';
		$msg = sprintf(
			'<p>%s</p>',
			sprintf(
				__('%s is sharing with you: <a href="%s">%s</a>', 'pop-genericforms'), 
				$form_data['name'],
				$form_data['target-url'],
				$form_data['target-title']
			)
		).($form_data['message'] ? sprintf( 
			$placeholder, 
			__('Additional message', 'pop-genericforms'), 
			$form_data['message']
		) : '');

		PoP_EmailSender_Utils::send_email($form_data['email'], $subject, $msg);
	}

	function share(&$errors, $atts) {

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
