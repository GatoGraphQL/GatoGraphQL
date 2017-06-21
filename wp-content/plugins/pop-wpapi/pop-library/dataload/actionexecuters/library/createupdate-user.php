<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_User {

	protected function get_role() {

		return 'subscriber';
	}

	protected function validatecontent(&$errors, $form_data) {

		if (empty($form_data['first_name'])) {
			$errors[] = __('The name cannot be empty', 'pop-wpapi');
		}

		// Validate email
		$user_email = $form_data['user_email'];
		if ( $user_email == '' ) {
			$errors[] = __('The e-mail cannot be empty', 'pop-wpapi' );
		} 
		elseif ( ! is_email( $user_email ) ) {
			$errors[] = __('The email address isn&#8217;t correct.', 'pop-wpapi');
		}

		$limited_email_domains = get_site_option( 'limited_email_domains' );
		if ( is_array( $limited_email_domains ) && empty( $limited_email_domains ) == false ) {
			$emaildomain = substr( $user_email, 1 + strpos( $user_email, '@' ) );
			if ( in_array( $emaildomain, $limited_email_domains ) == false )
				$errors[] = __('That email address is not allowed!', 'pop-wpapi');
		}
	}

	protected function validatecreatecontent(&$errors, $form_data) {

		// Check the username
		$user_login = $form_data['username'];
		if ( $user_login == '' ) {
			$errors[] = __('The username cannot be empty.', 'pop-wpapi');
		} 
		elseif ( ! validate_username( $user_login ) ) {
			$errors[] = __('This username is invalid because it uses illegal characters. Please enter a valid username.', 'pop-wpapi');
		} 
		elseif ( username_exists( $user_login ) ) {
			$errors[] = __('This username is already registered. Please choose another one.', 'pop-wpapi');
		}

		// Check the e-mail address
		$user_email = $form_data['user_email'];
		if ( email_exists( $user_email ) ) {
			$errors[] = __('This email is already registered, please choose another one.', 'pop-wpapi');
		}

		// Validate Password
		$password = $form_data['password'];
		$repeatpassword =  $form_data['repeat_password'];
		
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

		// Validate the captcha
		$captcha = $form_data['captcha'];
		
		$captcha_validation = GD_Captcha::validate($captcha['input'], $captcha['session']);
		if (is_wp_error($captcha_validation)) {
			$errors[] = $captcha_validation->get_error_message();
		}
	}

	protected function validateupdatecontent(&$errors, $form_data) {

		$user_id = $form_data['user_id'];
		$user_email = $form_data['user_email'];

		$email_user_id = email_exists($user_email);
		if ($email_user_id && $email_user_id !== $user_id ) {
			$errors[] = __('That email address already exists in our system!', 'pop-wpapi');
		}
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager; /*, $allowedposttags;*/

		$vars = GD_TemplateManager_Utils::get_vars();
		$user_id = $vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/ ? $vars['global-state']['current-user-id']/*get_current_user_id()*/ : '';
		$username = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUU_USERNAME)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUU_USERNAME, $atts);
		$form_data = array(
			'user_id' => $user_id,
			'username' => sanitize_user($username),
			'password' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORD)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORD, $atts),
			'repeat_password' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORDREPEAT)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORDREPEAT, $atts),
			'first_name' => trim($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUU_FIRSTNAME)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUU_FIRSTNAME, $atts)),
			'user_email' => trim($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUU_EMAIL)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUU_EMAIL, $atts)),
			'description' => trim($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUU_DESCRIPTION)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUU_DESCRIPTION, $atts)),
			'user_url' => trim($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUU_USERURL)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUU_USERURL, $atts)),
			// 'picture-uploadpath' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE)->get_value(GD_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE, $atts),
			'captcha' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CAPTCHA)->get_value(GD_TEMPLATE_FORMCOMPONENT_CAPTCHA, $atts)
		);

		// Allow to add extra inputs
		$form_data = apply_filters('gd_createupdate_user:form_data', $form_data, $atts);
		
		return $form_data;
	}

	protected function get_createuser_form_data($atts) {

		$form_data = $this->get_form_data($atts);

		// Allow to add extra inputs
		$form_data = apply_filters('gd_createupdate_user:form_data:create', $form_data, $atts);
		
		return $form_data;
	}

	protected function get_updateuser_form_data($atts) {

		$form_data = $this->get_form_data($atts);

		// Allow to add extra inputs
		$form_data = apply_filters('gd_createupdate_user:form_data:update', $form_data, $atts);
		
		return $form_data;
	}

	protected function get_display_name($form_data) {

		return $form_data['first_name'];
	}

	protected function get_updateuser_data($form_data) {

		$display_name = $this->get_display_name($form_data);
		$user_data = array(
			'ID' => $form_data['user_id'],
			'first_name' => $form_data['first_name'],
			'user_email' => $form_data['user_email'],
			'display_name' => $display_name,
			'nickname' => $display_name,
			'description' => $form_data['description'],
			'user_url' => $form_data['user_url']
		);

		return $user_data;
	}

	protected function get_createuser_data($form_data) {

		$user_data = $this->get_updateuser_data($form_data);
		
		// ID not needed
		unset($user_data['ID']);

		// Assign the role only when creating a user
		$user_data['role'] = $this->get_role();

		// Add the password
		$user_data['user_pass'] = $form_data['password'];

		// Username
		$user_data['user_login'] = $form_data['username'];

		return $user_data;
	}

	protected function execute_updateuser($user_data) {

		return wp_update_user($user_data);
	}

	protected function createupdateuser($user_id, $form_data) {
	}

	// protected function save_picture($user_id, $form_data) {

	// 	global $gd_useravatar_update;
	// 	$gd_useravatar_update->save_picture($user_id, $form_data['picture-uploadpath']);
	// }

	protected function updateuser(&$errors, $form_data) {

		$user_data = $this->get_updateuser_data($form_data);
		$user_id = $user_data['ID'];
		
		$result = $this->execute_updateuser($user_data);

		if (is_wp_error($result)) {
			$errors[] = sprintf(
				__('Ops, there was a problem: %s', 'pop-wpapi'),
				$result->get_error_message()
			);
			return;
		}

		$this->createupdateuser($user_id, $form_data);

		// Force WP to refetch the data for the logged in user
		// Eg: when changing "Do you accept members?" for an Organization, it will add/remove the role COMMUNITY, and if not flushed, the old values will persist
		global $current_user;
		$current_user = null;

		return $user_id;
	}

	protected function execute_createuser($user_data) {

		return wp_insert_user($user_data);
	}

	protected function createuser(&$errors, $form_data) {

		$user_data = $this->get_createuser_data($form_data);
		$result = $this->execute_createuser($user_data);

		if (is_wp_error($result)) {

			$errors[] = sprintf(
				__('Ops, there was a problem: %s', 'pop-wpapi'),
				$result->get_error_message()
			);
			return;
		}

		$user_id = $result;
		
		$this->createupdateuser($user_id, $form_data);
		// $this->save_picture($user_id, $form_data);

		return $user_id;
	}

	public function create_or_update(&$errors, $atts) {

		// If user is logged in => It's Update
		// Otherwise => It's Create
		
		$vars = GD_TemplateManager_Utils::get_vars();
		if ($vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {

			$this->update($errors, $atts);
			return 'update';
		}

		$this->create($errors, $atts);
		return 'create';
	}
	
	protected function additionals($user_id, $form_data) {

		do_action('gd_createupdate_user:additionals', $user_id, $form_data);
	}
	protected function additionals_update($user_id, $form_data) {

		do_action('gd_createupdate_user:additionals_update', $user_id, $form_data);
	}
	protected function additionals_create($user_id, $form_data) {

		do_action('gd_createupdate_user:additionals_create', $user_id, $form_data);
	}

	protected function update(&$errors, $atts) {

		$form_data = $this->get_updateuser_form_data($atts);

		$this->validatecontent($errors, $form_data);
		$this->validateupdatecontent($errors, $form_data);
		if ($errors) {
			return;
		}

		// Do the Post update
		$user_id = $this->updateuser($errors, $form_data);

		// Allow for additional operations (eg: set Action categories)
		$this->additionals_update($user_id, $form_data);
		$this->additionals($user_id, $form_data);
	}

	protected function create(&$errors, $atts) {

		$form_data = $this->get_createuser_form_data($atts);

		$this->validatecontent($errors, $form_data);
		$this->validatecreatecontent($errors, $form_data);
		if ($errors) {
			return;
		}

		$user_id = $this->createuser($errors, $form_data);

		// Allow for additional operations (eg: set Action categories)
		$this->additionals_create($user_id, $form_data);
		$this->additionals($user_id, $form_data);

		// Comment Leo 21/09/2015: we don't use this function anymore to send the notifications to the admin/user. Instead, use our own hooks.
		// Send notification of new user
		// wp_new_user_notification( $user_id, $form_data['password'] );
	}
}
