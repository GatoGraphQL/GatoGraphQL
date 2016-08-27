<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_LOSTPWD', 'lostpwd');

class GD_DataLoad_ActionExecuter_LostPassword extends GD_DataLoad_ActionExecuter {

	function __construct() {

		add_filter("retrieve_password_message", array($this, 'retrieve_password_message'), 100000, 4);
		parent::__construct();
	}

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_LOSTPWD;
	}

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		// If the post has been submitted, execute the Gravity Forms shortcode
		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			$result = $this->retrieve_password();
			if (is_wp_error($result)) {

				// Return error string
				return array(
					GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $result->get_error_messages()
				);
			}

			// Redirect to the "Reset password" page
			return array(
				GD_DATALOAD_IOHANDLER_FORM_SOFTREDIRECT => get_permalink(POP_WPAPI_PAGE_LOSTPWDRESET),
				GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true,
			);
		}

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}

	function retrieve_password_message($message, $key, $user_login, $user_data) {

		// $message = sprintf(
		// 	'<p>%s</p><br/>',
		// 	sprintf(
		// 		__('Someone requested that the password be reset for your account on <a href="%s">%s</a>', 'pop-wpapi'),
		// 		trailingslashit(home_url()),
		// 		get_bloginfo('name')
		// 	)
		// );
		$message = sprintf(
			'<p>%s</p>',
			sprintf(
				__('To reset your password, please copy the following code, and <a href="%s">paste it in the "Code" input</a>:', 'pop-wpapi'),
				get_permalink(POP_WPAPI_PAGE_LOSTPWDRESET)
			)
		);
		$message .= sprintf(
			'<p><pre style="%s">%s</pre></p><br/>',
			'background-color: #f1f1f2; width: 100%; padding: 5px;',
			$key.'&'.rawurlencode($user_login)
		);
		$message .= sprintf(
			'<p>%s</p>',
			__('If this was a mistake, or if it was not you who requested the password reset, just ignore this email and nothing will happen.', 'pop-wpapi')
		);

		return $message;
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager;

		$form_data = array(
			'user_login' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_LOSTPWD_USERNAME)->get_value(GD_TEMPLATE_FORMCOMPONENT_LOSTPWD_USERNAME, $atts),
		);
		
		return $form_data;
	}

	// Function copied exactly as it is from WP 4.3.1 file wp-login.php (We can't invoke it directly, since wp-login.php has not been loaded, and we can't do it since it executes a lot of unwanted code producing and output)
	// Only difference: we get the value of "user_login" using the template instead of getting it from $_POST['user_login']
	// This way: we can change "user_login" to something else, adding an extra layer of security against bots
	/**
	 * Handles sending password retrieval email to user.
	 *
	 * @global wpdb         $wpdb      WordPress database abstraction object.
	 * @global PasswordHash $wp_hasher Portable PHP password hashing framework.
	 *
	 * @return bool|WP_Error True: when finish. WP_Error on error
	 */
	function retrieve_password() {

		$form_data = $this->get_form_data($atts);
		$pop_user_login = $form_data['user_login'];

		// From here below, everything is (almost) the same as in the original file
		// Only difference: replaced $_POST['user_login'] with /*$_POST['user_login']*/$pop_user_login
		global $wpdb, $wp_hasher;

		$errors = new WP_Error();

		if ( empty( /*$_POST['user_login']*/$pop_user_login ) ) {
			$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.'));
		} elseif ( strpos( /*$_POST['user_login']*/$pop_user_login, '@' ) ) {
			$user_data = get_user_by( 'email', trim( /*$_POST['user_login']*/$pop_user_login ) );
			if ( empty( $user_data ) )
				$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
		} else {
			$login = trim(/*$_POST['user_login']*/$pop_user_login);
			$user_data = get_user_by('login', $login);
		}

		/**
		 * Fires before errors are returned from a password reset request.
		 *
		 * @since 2.1.0
		 */
		do_action( 'lostpassword_post' );

		if ( $errors->get_error_code() )
			return $errors;

		if ( !$user_data ) {
			$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.'));
			return $errors;
		}

		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;

		/**
		 * Fires before a new password is retrieved.
		 *
		 * @since 1.5.0
		 * @deprecated 1.5.1 Misspelled. Use 'retrieve_password' hook instead.
		 *
		 * @param string $user_login The user login name.
		 */
		do_action( 'retreive_password', $user_login );

		/**
		 * Fires before a new password is retrieved.
		 *
		 * @since 1.5.1
		 *
		 * @param string $user_login The user login name.
		 */
		do_action( 'retrieve_password', $user_login );

		/**
		 * Filter whether to allow a password to be reset.
		 *
		 * @since 2.7.0
		 *
		 * @param bool true           Whether to allow the password to be reset. Default true.
		 * @param int  $user_data->ID The ID of the user attempting to reset a password.
		 */
		$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

		if ( ! $allow ) {
			return new WP_Error( 'no_password_reset', __('Password reset is not allowed for this user') );
		} elseif ( is_wp_error( $allow ) ) {
			return $allow;
		}

		// Generate something random for a password reset key.
		$key = wp_generate_password( 20, false );

		/**
		 * Fires when a password reset key is generated.
		 *
		 * @since 2.5.0
		 *
		 * @param string $user_login The username for the user.
		 * @param string $key        The generated password reset key.
		 */
		do_action( 'retrieve_password_key', $user_login, $key );

		// Now insert the key, hashed, into the DB.
		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . WPINC . '/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
		}
		$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
		$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );

		$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
		$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
		$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
		$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";

		if ( is_multisite() )
			$blogname = $GLOBALS['current_site']->site_name;
		else
			/*
			 * The blogname option is escaped with esc_html on the way into the database
			 * in sanitize_option we want to reverse this for the plain text arena of emails.
			 */
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

		$title = sprintf( __('[%s] Password Reset'), $blogname );

		/**
		 * Filter the subject of the password reset email.
		 *
		 * @since 2.8.0
		 *
		 * @param string $title Default email title.
		 */
		$title = apply_filters( 'retrieve_password_title', $title );

		/**
		 * Filter the message body of the password reset mail.
		 *
		 * @since 2.8.0
		 * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
		 *
		 * @param string  $message    Default mail message.
		 * @param string  $key        The activation key.
		 * @param string  $user_login The username for the user.
		 * @param WP_User $user_data  WP_User object.
		 */
		$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

		if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) )
			wp_die( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.') );

		return true;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_LostPassword();