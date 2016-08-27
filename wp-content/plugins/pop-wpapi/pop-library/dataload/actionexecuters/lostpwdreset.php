<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_LOSTPWDRESET', 'lostpwdreset');

class GD_DataLoad_ActionExecuter_LostPasswordReset extends GD_DataLoad_ActionExecuter {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_LOSTPWDRESET;
	}

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		// If the post has been submitted, execute the Gravity Forms shortcode
		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			global $gd_template_processor_manager;
			$code = trim($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_CODE)->get_value(GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_CODE, $block_atts));
			$pwd = trim($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_NEWPASSWORD)->get_value(GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_NEWPASSWORD, $block_atts));
			$repeatpwd = trim($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_PASSWORDREPEAT)->get_value(GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_PASSWORDREPEAT, $block_atts));
			
			$errorcodes = array();
			if ($code) {
				list($rp_key, $rp_login) = explode('&', wp_unslash($code), 2);
				$rp_login = rawurldecode($rp_login);

				if (!$rp_key || !$rp_login) {
					$errorcodes[] = 'error-wrongcode';
				}
				else {
					$user = check_password_reset_key( $rp_key, $rp_login );
					if (!$user || is_wp_error($user)) {
					
						$errorcodes[] = 'error-invalidkey';
					}
				}
			}
			else {
				$errorcodes[] = 'error-nocode';
			}

			if (!$pwd) {
				$errorcodes[] = 'error-nopwd';
			}
			elseif (strlen($pwd) < 8) {
				$errorcodes[] = 'error-short';
			}
			if (!$repeatpwd) {
				$errorcodes[] = 'error-norepeatpwd';
			}
			if ($pwd != $repeatpwd) {
				$errorcodes[] = 'error-pwdnomatch';
			}

			// Code below copied from WP 4.3.1 file wp-login.php
			

			// Return error string
			if ($errorcodes) {
				return array(
					GD_DATALOAD_IOHANDLER_FORM_ERRORCODES => $errorcodes
				);
			}

			// Hooks
			$errors = new WP_Error();

			/**
			 * Fires before the password reset procedure is validated.
			 *
			 * @since 3.5.0
			 *
			 * @param object           $errors WP Error object.
			 * @param WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
			 */
			do_action( 'validate_password_reset', $errors, $user );

			if ( $errormessages = $errors->get_error_messages() ) {
				
				return array(
					GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $errormessages
				);
			}

			// Do the actual password reset
			reset_password($user, $pwd);

			do_action('gd_lostpasswordreset', $user->ID);

			return array(
				GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
			);
		}

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_LostPasswordReset();