<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_LOGOUT', 'logout');

class GD_DataLoad_ActionExecuter_Logout extends GD_DataLoad_ActionExecuter {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_LOGOUT;
	}

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		// Log the user out
		// wp_logout();

		// return parent::execute($block_data_settings, $block_atts, $block_execution_bag);

		// If the post has been submitted, execute the Gravity Forms shortcode
		if ('POST' == $_SERVER['REQUEST_METHOD']) { 

			// If the user is not logged in, then return the error
			$vars = GD_TemplateManager_Utils::get_vars();
			if (!$vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {

				$error = __('You are not logged in.', 'pop-wpapi');
			
				// Return error string
				return array(
					GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => array($error)
				);
			}

			$user_id = $vars['global-state']['current-user-id']/*get_current_user_id()*/;
			wp_logout();

			// Delete the current user, so that it already says "user not logged in" for the toplevel feedback
			global $current_user;
			$current_user = null;
			wp_set_current_user(0);

			// Modify the global-state with the newly logged in user info
			PoP_WPAPI_Engine_Utils::update_global_user_state(GD_TemplateManager_Utils::$vars);;

			do_action('gd:user:loggedout', $user_id);

			// Return the redirect. Use Hard redirect
			return array(
				GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
			);


			// // Return the redirect. Use Hard redirect
			// global $gd_template_processor_manager;
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
new GD_DataLoad_ActionExecuter_Logout();