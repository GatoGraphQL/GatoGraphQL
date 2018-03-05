<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ActionExecuterInstance_Volunteer {

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

		if (empty($form_data['target-id'])) {
			
			$errors[] = __('The requested post cannot be empty.', 'pop-genericforms');
		}
		else {

			// Make sure the post exists
			$target = get_post($form_data['target-id']);
			if (!$target) {

				$errors[] = __('The requested post does not exist.', 'pop-genericforms');
			}
		}

		if (empty($form_data['whyvolunteer'])) {
			$errors[] = __('Why volunteer cannot be empty.', 'pop-genericforms');
		}
	}

	/**
	 * Function to override
	 */
	protected function additionals($form_data) {

		do_action('pop_volunteer', $form_data);
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;

		$form_data = array(
			'name' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_NAME)->get_value(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts),
			'email' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_EMAIL)->get_value(GD_TEMPLATE_FORMCOMPONENT_EMAIL, $atts),
			'phone' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_PHONE)->get_value(GD_TEMPLATE_FORMCOMPONENT_PHONE, $atts),
			'whyvolunteer' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER)->get_value(GD_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER, $atts),
			'target-id' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_POSTID)->get_value(GD_TEMPLATE_FORMCOMPONENT_POSTID, $atts),
		);
		
		return $form_data;
	}

	protected function execute($form_data) {

		$post_title = get_the_title($form_data['target-id']);
		$subject = sprintf(
			__('[%s]: %s', 'pop-genericforms'), 
			get_bloginfo('name'),
			sprintf(
				__('%s applied to volunteer for %s', 'pop-genericforms'), 
				$form_data['name'],
				$post_title
			)
		);
		$placeholder = '<p><b>%s:</b> %s</p>';
		$msg = sprintf(
			'<p>%s</p>',
			__('You have a new volunteer! Please contact the volunteer directly through the contact details below.', 'pop-genericforms')
		).sprintf(
			'<p>%s</p>',
			sprintf(
				__('%s applied to volunteer for: <a href="%s">%s</a>', 'pop-genericforms'), 
				$form_data['name'],
				get_permalink($form_data['target-id']),
				$post_title
			)
		).sprintf( 
			$placeholder, 
			__('Email', 'pop-genericforms'), 
			sprintf(
				'<a href="mailto:%1$s">%1$s</a>',
				$form_data['email']
			)
		).sprintf( 
			$placeholder, 
			__('Phone', 'pop-genericforms'), 
			$form_data['phone']
		).sprintf( 
			$placeholder, 
			__('Why volunteer', 'pop-genericforms'), 
			$form_data['whyvolunteer']
		);

		PoP_EmailSender_Utils::sendemail_to_users_from_post(array($form_data['target-id']), $subject, $msg);
	}

	function volunteer(&$errors, $atts) {

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
