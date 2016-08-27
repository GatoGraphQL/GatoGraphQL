<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_ChangePassword_User {

	protected function validate(&$errors, $form_data) {

		// Validate Password
		// Check current password really belongs to the user
		$user = get_user_by('id', $form_data['user_id']);
		$current_password = $form_data['current_password'];
		$password = $form_data['password'];
		$repeatpassword =  $form_data['repeat_password'];

		if (!$current_password) {
			$errors[] = __('Please provide the current password.', 'pop-wpapi');
		}
		elseif (!wp_check_password($current_password, $user->user_pass)) {
			$errors[] = __('Current password is incorrect.', 'pop-wpapi');
		}
		
		if (!$password) {
			$errors[] = __('The password cannot be emtpy.', 'pop-wpapi');
		}
		elseif (strlen($password) < 8) {
			$errors[] = __('The password must be at least 8 characters long.', 'pop-wpapi');
		}
		else {
			if (!$repeatpassword) {
				$errors[] = __('Please confirm the password.', 'pop-wpapi');
			}
			elseif ($password !== $repeatpassword) {
				$errors[] = __('Passwords do not match.', 'pop-wpapi');						
			}
		}
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager, $allowedposttags;

		$user_id = get_current_user_id();
		$form_data = array(
			'user_id' => $user_id,
			'current_password' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUU_CURRENTPASSWORD)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUU_CURRENTPASSWORD, $atts),
			'password' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORD)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORD, $atts),
			'repeat_password' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORDREPEAT)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORDREPEAT, $atts)
		);
		
		return $form_data;
	}

	protected function execute_changepassword($user_data) {

		return wp_update_user($user_data);
	}

	protected function get_changepassword_data($form_data) {

		$user_data = array(
			'ID' => $form_data['user_id'],
			'user_pass' => $form_data['password']
		);

		return $user_data;
	}

	protected function changepassworduser(&$errors, $form_data) {

		$user_data = $this->get_changepassword_data($form_data);
		$result = $this->execute_changepassword($user_data);

		if (is_wp_error($result)) {

			$errors[] = sprintf(
				__('Ops, there was a problem: %s', 'pop-wpapi'),
				$result->get_error_message()
			);
			return;
		}

		$user_id = $user_data['ID'];

		do_action('gd_changepassword_user', $user_id, $form_data);

		return $user_id;
	}

	function changepassword(&$errors, $atts) {

		$form_data = $this->get_form_data($atts);

		$this->validate($errors, $form_data);
		if ($errors) {
			return;
		}

		// Do the password change
		return $this->changepassworduser($errors, $form_data);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_changepassword_user;
$gd_changepassword_user = new GD_ChangePassword_User();