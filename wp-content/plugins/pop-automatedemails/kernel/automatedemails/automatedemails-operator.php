<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_AutomatedEmails_Operator {
    
    function __construct() {
    
		add_action('PoP_Engine:rendered', array($this, 'maybe_send_automatedemail'));
	}
	
    function maybe_send_automatedemail() {

    	global $pop_automatedemails_manager;
		$vars = GD_TemplateManager_Utils::get_vars();

		// Never send the emails if fetching data (configuration is not created => error)
		if ($vars['fetching-json-data']) return;

		if ($vars['global-state']['is-page']/*is_page()*/) {

			$post = $vars['global-state']['post']/*global $post*/;
			if ($automatedemail = $pop_automatedemails_manager->get_automatedemail($post->ID)) {
				
				foreach ($automatedemail->get_emails() as $email) {
					
					$useremails = $names = array();
					foreach ($email['recipients'] as $user_id) {
						$useremails[] = get_the_author_meta('user_email', $user_id);
						$names[] = get_the_author_meta('display_name', $user_id);
					}

					PoP_EmailSender_Utils::sendemail_to_users($useremails, $names, $email['subject'], $email['content'], true);
				}
			}
		}
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_AutomatedEmails_Operator();
