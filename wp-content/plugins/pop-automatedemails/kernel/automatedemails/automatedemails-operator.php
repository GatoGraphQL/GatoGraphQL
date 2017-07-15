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
			if ($automatedemails = $pop_automatedemails_manager->get_automatedemails($post->ID)) {
				
				foreach ($automatedemails as $automatedemail) {
				
					// Allow to change the header to 'newsletter' under PoPTheme Wassup
					$header = apply_filters(
						'PoP_AutomatedEmails_Operator:automatedemail:header',
						null
					);
					foreach ($automatedemail->get_emails() as $email) {
						
						$users = $email['users'] ?? array();
						$recipients = $email['recipients'] ?? array();
						if ($users || $recipients) {

							$useremails = $names = array();
							foreach ($users as $user_id) {
								$useremails[] = get_the_author_meta('user_email', $user_id);
								$names[] = get_the_author_meta('display_name', $user_id);
							}

							// Allow Gravity Forms to already send a list of useremails and names
							foreach ($recipients as $recipient) {
								$useremails[] = $recipient['email'];
								$names[] = $recipient['name'];
							}

							// // Comment Leo: descomentar acá
							// echo PHP_EOL.PHP_EOL;
							// echo 'USERS: '.implode(',', $names).PHP_EOL;
							// echo 'EMAILS: '.implode(',', $useremails).PHP_EOL;
							// echo 'CONTENT: '.PHP_EOL;
							// echo $email['content'];
							// echo PHP_EOL;
							PoP_EmailSender_Utils::sendemail_to_users($useremails, $names, $email['subject'], $email['content'], true, $header, $email['frame']);
						}
					}
				}
			}
		}
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_AutomatedEmails_Operator();
