<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_LOGIN', 'login');

class GD_DataLoad_ActionExecuter_Login extends GD_DataLoad_ActionExecuter {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_LOGIN;
	}

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		// If the post has been submitted, execute the Gravity Forms shortcode
		if ('POST' == $_SERVER['REQUEST_METHOD']) { 

			$error = '';

			// If the user is already logged in, then return the error
			$vars = GD_TemplateManager_Utils::get_vars();
			if ($vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {
				$user = $vars['global-state']['current-user']/*wp_get_current_user()*/;
				$error = sprintf(
					__('You are already logged in as <a href="%s">%s</a>, <a href="%s">logout</a>?', 'pop-wpapi'),
					get_author_posts_url($user->ID),
					$user->display_name,
					wp_logout_url()
				);
			}
			else {

				global $gd_template_processor_manager;
				$username_or_email = trim($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_LOGIN_USERNAME)->get_value(GD_TEMPLATE_FORMCOMPONENT_LOGIN_USERNAME, $block_atts));
				$pwd = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_LOGIN_PWD)->get_value(GD_TEMPLATE_FORMCOMPONENT_LOGIN_PWD, $block_atts);

				if ($username_or_email && $pwd) {

					// Find out if it was a username or an email that was provided
					$is_email = strpos($username_or_email, '@');
					if ($is_email) {
						
						if ($user_data = get_user_by('email', $username_or_email)) {
							
							$username = $user_data->user_login;
						}
						else {
							
							$error = __('There is no user registered with that email address.');
						}
					} 
					else {
						$username = $username_or_email;
					}

					if ($username) {

						$credentials = array(
							'user_login' => $username,
							'user_password' => $pwd,
							'remember' => true,
						);
						$loginResult = wp_signon($credentials);

						if ( !(strtolower(get_class($loginResult)) == 'wp_user' )) {
							
							if (strtolower(get_class($loginResult)) == 'wp_error' ) {

								// If the user logged in with the email, and the error code is 'incorrect_password', then the error message
								// is: The password you entered for the username %s is incorrect
								// This is giving extra info on the connection between email and username, with potential security issues, so replace the error
								if ($is_email && ($loginResult->get_error_code() == 'incorrect_password')) {

									$error = sprintf(
										/* translators: %s: user name */
										__('<strong>ERROR</strong>: The password you entered for the email %s is incorrect.', 'pop-wpapi'),
										'<strong>' . $username_or_email . '</strong>'
									) .
									' <a href="' . wp_lostpassword_url() . '">' .
									__( 'Lost your password?' ) .
									'</a>';
								}
								else {
							
									$error = $loginResult->get_error_message();
								}
							}
							else {
							
								$error = __('An undefined error has ocurred', 'pop-wpapi');
							}
						}
					}
				}
				else {
					$error = __('Please supply your username and password.', 'pop-wpapi');
				}
			}

			if ($error) {

				// Return error string
				return array(
					GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => array($error)
				);
			}

			// Set the current user, so that it already says "user logged in" for the toplevel feedback
			$user = $loginResult;
			wp_set_current_user($user->ID);

			// Modify the global-state with the newly logged in user info
			PoP_WPAPI_Engine_Utils::update_global_user_state(GD_TemplateManager_Utils::$vars);

			do_action('gd:user:loggedin', $user->ID);

			return array(
				GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
			);

			// Return the redirect. Use Hard redirect
			// $redirect_to = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_BROWSERURL)->get_value(GD_TEMPLATE_FORMCOMPONENT_BROWSERURL, $block_atts);
			// if (!$redirect_to) {
				
			// 	$redirect_to = home_url();
			// }
			// return array(
			// 	GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true,
			// 	GD_DATALOAD_IOHANDLER_FORM_HARDREDIRECT => $redirect_to
			// );
		}

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_Login();