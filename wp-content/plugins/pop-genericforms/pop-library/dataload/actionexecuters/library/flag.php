<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ActionExecuterInstance_Flag {

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

		if (empty($form_data['whyflag'])) {
			$errors[] = __('Why flag cannot be empty.', 'pop-genericforms');
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
	}

	/**
	 * Function to override
	 */
	protected function additionals($form_data) {

		do_action('pop_flag', $form_data);
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;

		$form_data = array(
			'name' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_NAME)->get_value(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts),
			'email' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_EMAIL)->get_value(GD_TEMPLATE_FORMCOMPONENT_EMAIL, $atts),
			'whyflag' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_WHYFLAG)->get_value(GD_TEMPLATE_FORMCOMPONENT_WHYFLAG, $atts),
			'target-id' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_POSTID)->get_value(GD_TEMPLATE_FORMCOMPONENT_POSTID, $atts),
		);
		
		return $form_data;
	}

	protected function execute($form_data) {

		$to = PoP_EmailSender_Utils::get_admin_notifications_email();		
		$subject = sprintf(
			__('[%s]: %s', 'pop-genericforms'), 
			get_bloginfo('name'),
			__('Flag post', 'pop-genericforms')
		);
		$target = get_post($form_data['target-id']);
		$placeholder = '<p><b>%s:</b> %s</p>';
		$msg = sprintf(
			'<p>%s</p>',
			__('New post flagged by user', 'pop-genericforms')
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
			__('Post ID', 'pop-genericforms'), 
			$form_data['target-id']
		).sprintf( 
			$placeholder, 
			__('Post title', 'pop-genericforms'), 
			get_the_title($form_data['target-id'])
		).sprintf( 
			$placeholder, 
			__('Why flag', 'pop-genericforms'), 
			$form_data['whyflag']
		);

		PoP_EmailSender_Utils::send_email($to, $subject, $msg);
	}

	function flag(&$errors, $atts) {

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
