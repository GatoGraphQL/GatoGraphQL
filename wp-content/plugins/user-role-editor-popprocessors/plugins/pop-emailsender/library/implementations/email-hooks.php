<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_EMAIL_JOINSCOMMUNITIES', 'joinscommunities');

class PoP_URE_EmailSender_Hooks extends PoP_EmailSender_Hooks {

	// Needed to keep track of what users have an email sent to, to not duplicate email sending
	private $sent_emails;

	function __construct() {

		$this->sent_emails = array(
			POP_EMAIL_JOINSCOMMUNITIES => array(),
		);
		
		//----------------------------------------------------------------------
		// Email Notifications
		//----------------------------------------------------------------------
		// URE_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:
		add_action('gd_update_mycommunities:update', array($this, 'emailnotifications_network_joinscommunity'), 10, 3);
	}

	function emailnotifications_network_joinscommunity($user_id, $form_data, $operationlog) {

		if (!$communities = $operationlog['new-communities']) return;

		// Get the current user's network's users (followers + members of same communities)
		$networkusers = $this->get_user_networkusers($user_id);
		if ($networkusers = array_diff($networkusers, $this->sent_emails[POP_EMAIL_JOINSCOMMUNITIES])) {

			// Keep only the users with the corresponding preference on
			if ($networkusers = PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_URE_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY, $networkusers)) {

				$emails = $names = array();
				foreach ($networkusers as $networkuser) {

					$emails[] = get_the_author_meta( 'user_email', $networkuser );
					$names[] = get_the_author_meta( 'display_name', $networkuser );
				}

				$user_url = get_author_posts_url($user_id);
				$user_name = get_the_author_meta( 'display_name', $user_id);
				$community_names = array();
				foreach ($communities as $community) {

					$community_names[] = get_the_author_meta( 'display_name', $community);
				}
				$subject = sprintf(
					__( '%s has joined %s', 'pop-emailsender'), 
					$user_name,
					implode(
						__(', ', 'pop-emailsender'),
						$community_names
					)
				);
				
				$content = sprintf(
					__( '<p><a href="%s">%s</a> has joined:</p>', 'pop-emailsender'),
					$user_url,
					$user_name
				);

				$instance = PoP_EmailTemplates_Factory::get_instance();
				foreach ($communities as $community) {

					$content .= $instance->get_userhtml($community);
				}

				$footer = $this->get_preferences_footer(__('You are receiving this notification for belonging to the user’s network.', 'pop-emailsender'));
				$content .= $footer;
				
				PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content);

				// Add the users to the list of users who got an email sent to
				$this->sent_emails[POP_EMAIL_JOINSCOMMUNITIES] = array_merge(
					$this->sent_emails[POP_EMAIL_JOINSCOMMUNITIES],
					$networkusers
				);
			}			
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_URE_EmailSender_Hooks();

