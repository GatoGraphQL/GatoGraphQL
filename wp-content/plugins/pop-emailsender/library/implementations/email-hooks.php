<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_EMAIL_CREATEDPOST', 'created-post');
define ('POP_EMAIL_ADDEDCOMMENT', 'added-comment');
define ('POP_EMAIL_FOLLOWSUSER', 'followsuser');
define ('POP_EMAIL_RECOMMENDSPOST', 'recommendspost');
define ('POP_EMAIL_SUBSCRIBEDTOTOPIC', 'subscribedtotopic');
define ('POP_EMAIL_UPDOWNVOTEDPOST', 'updownvotedpost');
// define ('POP_EMAIL_USERMENTIONED', 'usermentioned');

class PoP_EmailSender_Hooks {

	// Needed to keep track of what users have an email sent to, to not duplicate email sending
	private $sent_emails;

	function __construct() {

		$this->sent_emails = array(
			POP_EMAIL_CREATEDPOST => array(),
			POP_EMAIL_ADDEDCOMMENT => array(),
			POP_EMAIL_FOLLOWSUSER => array(),
			POP_EMAIL_RECOMMENDSPOST => array(),
			POP_EMAIL_SUBSCRIBEDTOTOPIC => array(),
			POP_EMAIL_UPDOWNVOTEDPOST => array(),
			// POP_EMAIL_USERMENTIONED => array(),
		);
		
		// Important: do not change the order of the hooks added below, because users receive only 1 email for each type (eg: added a post),
		// so if they suit 2 different hooks (eg: general preferences and user network preferences) then send it under the most specific one (eg: user network preferences)
		
		//----------------------------------------------------------------------
		// Notifications to the admin
		//----------------------------------------------------------------------
		add_action('gd_createupdate_post:update', array($this, 'sendemail_to_admin_updatepost'), 100, 1);
		add_action('gd_createupdate_post:create', array($this, 'sendemail_to_admin_createpost'), 100, 1);
		add_action('gd_createupdate_profile:additionals_create', array($this, 'sendemail_to_admin_createuser'), 100, 1);
		add_action('gd_createupdate_profile:additionals_update', array($this, 'sendemail_to_admin_updateuser'), 100, 1);
		
		//----------------------------------------------------------------------
		// Functional emails
		//----------------------------------------------------------------------
		// Password lost
		add_action('retrieve_password_key', array($this, 'retrieve_password_key'));
		add_filter('retrieve_password_title', 'retrieve_password_title');
		add_filter('retrieve_password_message', 'retrievepasswordmessage', 999999, 4);
		add_action('gd_lostpasswordreset', array($this, 'lostpasswordreset'), 10, 1);
		add_filter('send_password_change_email', array($this, 'donotsend'), 100000, 1);
		add_filter('send_email_change_email', array($this, 'donotsend'), 100000, 1);
		
		// Post created/updated/approved
		add_action('gd_createupdate_post:create', array($this, 'sendemail_to_users_from_post_create'), 100, 1);
		add_action('pending_to_publish', array($this, 'sendemail_to_users_from_post_postapproved'), 10, 1);
		add_action('gd_createupdate_post:create', array($this, 'sendemail_to_users_from_post_referencescreate'), 10, 1);
		add_action('gd_createupdate_post:update', array($this, 'sendemail_to_users_from_post_referencesupdate'), 10, 3);
		add_action('pending_to_publish', array($this, 'sendemail_to_users_from_post_referencestransition'), 10, 1);
		
		// Comments added
		add_action('wp_insert_comment', array($this, 'sendemail_to_users_from_comment'), 10, 2 );
		
		// User tagged
		add_action('PoP_Mentions:post_tags:tagged_users', array($this, 'sendemail_to_users_tagged_in_post'), 10, 3);
		add_action('PoP_Mentions:comment_tags:tagged_users', array($this, 'sendemail_to_users_tagged_in_comment'), 10, 2);
		
		// User followed
		add_action('gd_followuser', array($this, 'followuser'), 10, 1);
		
		// Post recommended
		add_action('gd_recommendpost', array($this, 'recommendpost'), 10, 1);

		//----------------------------------------------------------------------
		// Email Notifications
		//----------------------------------------------------------------------
		// EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST:
		add_action('gd_createupdate_post:create', array($this, 'emailnotifications_subscribedtopic_createdpost_create'), 10, 1);
		add_action('gd_createupdate_post:update', array($this, 'emailnotifications_subscribedtopic_createdpost_update'), 10, 3);
		// EMAILNOTIFICATIONS_NETWORK_CREATEDPOST:
		add_action('gd_createupdate_post:create', array($this, 'emailnotifications_network_createdpost_create'), 10, 1);
		add_action('gd_createupdate_post:update', array($this, 'emailnotifications_network_createdpost_update'), 10, 3);
		// EMAILNOTIFICATIONS_GENERAL_NEWPOST:
		add_action('gd_createupdate_post:create', array($this, 'emailnotifications_general_newpost_create'), 10, 1);
		add_action('gd_createupdate_post:update', array($this, 'emailnotifications_general_newpost_update'), 10, 3);
		// EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT:
		add_action('wp_insert_comment', array($this, 'emailnotifications_subscribedtopic_addedcomment'), 10, 2);
		// EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT:
		add_action('wp_insert_comment', array($this, 'emailnotifications_network_addedcomment'), 10, 2);
		// EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST:
		add_action('gd_recommendpost', array($this, 'emailnotifications_network_recommendedpost'), 10, 1);
		// EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER:
		add_action('gd_followuser', array($this, 'emailnotifications_network_followeduser'), 10, 1);
		// EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC:
		add_action('gd_subscribetotag', array($this, 'emailnotifications_network_subscribedtotopic'), 10, 1);
		// EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST:
		add_action('gd_upvotepost', array($this, 'emailnotifications_network_upvotedpost'), 10, 1);
		add_action('gd_downvotepost', array($this, 'emailnotifications_network_downvotedpost'), 10, 1);
		
		// // EMAILNOTIFICATIONS_DIGESTS_DAILY_NEWCONTENT:
		// add_action('', array($this, 'emailnotifications_digests_daily_newcontent'), 10, 1);
		// // EMAILNOTIFICATIONS_DIGESTS_DAILY_UPCOMINGEVENTS:
		// add_action('', array($this, 'emailnotifications_digests_daily_upcomingevents'), 10, 1);
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Email Notifications
	 * ---------------------------------------------------------------------------------------------------------------*/
	
	protected function get_preferences_footer($msg) {

		return sprintf(
			'<p><small>%s</small></p>',
			sprintf(
				__('%s You can edit your preferences for email notifications <a href="%s">here</a>.', 'pop-emailsender'),
				$msg,
				get_permalink(POP_COREPROCESSORS_PAGE_MYPREFERENCES)
			)
		);
	}
	/**---------------------------------------------------------------------------------------------------------------
	 * User's network notification emails: post created
	 * ---------------------------------------------------------------------------------------------------------------*/
	function emailnotifications_network_createdpost_create($post_id) {

		// Send email if the created post has been published
		if (get_post_status($post_id) == 'publish') {
			$this->sendemail_to_usersnetwork_from_post($post_id);
		}
	}
	function emailnotifications_network_createdpost_update($post_id, $atts, $log) {

		// Send an email to all post owners's network when a post is published
		$old_status = $log['previous-status'];

		// Send email if the updated post has been published
		if (get_post_status($post_id) == 'publish' && $old_status != 'publish') {
			$this->sendemail_to_usersnetwork_from_post($post_id);
		}
	}
	protected function sendemail_to_usersnetwork_from_post($post_id) {

		// Check if for a given type of post the email must not be sent (eg: Highlights)
		if (PoP_EmailSender_Utils::sendemail_skip($post_id)) {
			return;
		}

		// Do not send for RIPESS
		if (!apply_filters('PoP_EmailSender_Hooks:sendemail_to_usersnetwork_from_post:enabled', true)) {
			return;
		}

		// No need to check if the post_status is "published", since it's been checked in the previous 2 functions (create/update)
		$post_html = PoP_EmailTemplates_Factory::get_instance()->get_posthtml($post_id);
		$post_name = gd_get_postname($post_id);
		$post_title = get_the_title($post_id);
		$footer = $this->get_preferences_footer(__('You are receiving this notification for belonging to the authors’ network.', 'pop-emailsender'));

		// $allnetworkusers = array();
		$authors = gd_get_postauthors($post_id);
		foreach ($authors as $author) {

			// Get all the author's network's users (followers + members of same communities)
			$networkusers = get_user_networkusers($author);

			// Do not send email to the authors of the post, they know already!
			$networkusers = array_diff($networkusers, $authors);

			// From those, remove all users who got an email in a previous email function
			if ($networkusers = array_diff($networkusers, $this->sent_emails[POP_EMAIL_CREATEDPOST])) {

				// Keep only the users with the corresponding preference on
				if ($networkusers = PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST, $networkusers)) {

				// // If post has co-authors, and these have the same follower, then do not send the same email to the follower for each co-author
				// if ($networkusers = array_diff($networkusers, $allnetworkusers)) {

				// 	$allnetworkusers = array_merge(
				// 		$allnetworkusers,
				// 		$networkusers
				// 	);

					$emails = $names = array();
					foreach ($networkusers as $networkuser) {

						$emails[] = get_the_author_meta( 'user_email', $networkuser );
						$names[] = get_the_author_meta( 'display_name', $networkuser );
					}

					$author_name = get_the_author_meta('display_name', $author);
					$author_url = get_author_posts_url($author);
					$subject = sprintf(
						__('%s has created a new %s: “%s”', 'pop-emailsender'), 
						$author_name,
						$post_name,
						$post_title
					);
					$content = sprintf( 
						'<p>%s</p>', 
						sprintf(
							__('<b><a href="%s">%s</a></b> has created a new %s:', 'pop-emailsender'), 
							$author_url,
							$author_name,
							$post_name
						)
					);
					$content .= $post_html;
					$content .= $footer;

					PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content, true);

					$this->sent_emails[POP_EMAIL_CREATEDPOST] = array_merge(
						$this->sent_emails[POP_EMAIL_CREATEDPOST],
						$networkusers
					);
				}		
			}	
		}

		// // Add the users to the list of users who got an email sent to
		// $this->sent_emails[POP_EMAIL_CREATEDPOST] = array_merge(
		// 	$this->sent_emails[POP_EMAIL_CREATEDPOST],
		// 	$allnetworkusers
		// );
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Subscribed tags/topics: post created
	 * ---------------------------------------------------------------------------------------------------------------*/
	// Send an email to all post owners's network when a post is published
	function emailnotifications_subscribedtopic_createdpost_update($post_id, $atts, $log) {

		$old_status = $log['previous-status'];

		// Send email if the updated post has been published
		if (get_post_status($post_id) == 'publish' && $old_status != 'publish') {
			$this->sendemail_to_subscribedtagusers_from_post($post_id);
		}
	}
	function emailnotifications_subscribedtopic_createdpost_create($post_id) {

		// Send email if the created post has been published
		if (get_post_status($post_id) == 'publish') {
			$this->sendemail_to_subscribedtagusers_from_post($post_id);
		}
	}
	protected function sendemail_to_subscribedtagusers_from_post($post_id) {

		// Check if for a given type of post the email must not be sent (eg: Highlights)
		if (PoP_EmailSender_Utils::sendemail_skip($post_id)) {
			return;
		}

		// If the post has tags...
		if ($post_tags = wp_get_post_tags($post_id, array('fields' => 'ids'))) {

			$post_html = PoP_EmailTemplates_Factory::get_instance()->get_posthtml($post_id);
			$post_name = gd_get_postname($post_id);
			$post_title = get_the_title($post_id);
			$footer = $this->get_preferences_footer(__('You are receiving this notification for having subscribed to tags/topics added in this post.', 'pop-emailsender'));

			foreach ($post_tags as $tag_id) {

				// Get all the users who subscribed to each tag
				if ($tag_subscribers = GD_MetaManager::get_term_meta($tag_id, GD_METAKEY_TERM_SUBSCRIBEDBY)) {

					// From those, remove all users who got an email in a previous email function
					if ($tag_subscribers = array_diff($tag_subscribers, $this->sent_emails[POP_EMAIL_CREATEDPOST])) {

						// Keep only the users with the corresponding preference on
						// Do not send to the current user
						if ($tag_subscribers = PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST, $tag_subscribers, array(get_current_user_id()))) {

							$emails = $names = array();
							foreach ($tag_subscribers as $subscribeduser) {

								$emails[] = get_the_author_meta('user_email', $subscribeduser);
								$names[] = get_the_author_meta('display_name', $subscribeduser);
							}

							$tag = get_tag($tag_id);
							$tag_url = get_tag_link($tag_id);
							$tag_name = PoP_TagUtils::get_tag_symbol().$tag->name;
							$subject = sprintf(
								__('There is a new %s tagged with “%s”: “%s”', 'pop-emailsender'), 
								$post_name,
								$tag_name,
								$post_title
							);
							
							$content = sprintf( 
								'<p>%s</p>', 
								sprintf(
									__('There is a new %s, tagged with <a href="%s">%s</a>:', 'pop-emailsender'), 
									$post_name,
									$tag_url,
									$tag_name
								)
							);
							$content .= $post_html;
							$content .= $footer;

							PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content, true);

							// Add the users to the list of users who got an email sent to
							$this->sent_emails[POP_EMAIL_CREATEDPOST] = array_merge(
								$this->sent_emails[POP_EMAIL_CREATEDPOST],
								$tag_subscribers
							);
						}
					}
				}
			}
		}
	}
	// protected function sendemail_to_subscribedtagusers_from_post($post_id) {

	// 	// Check if for a given type of post the email must not be sent (eg: Highlights)
	// 	if (PoP_EmailSender_Utils::sendemail_skip($post_id)) {
	// 		return;
	// 	}

	// 	// Do not send for RIPESS
	// 	if (!apply_filters('PoP_EmailSender_Hooks:sendemail_to_subscribedtagusers_from_post:enabled', true)) {
	// 		return;
	// 	}

	// 	// If the post has tags...
	// 	if ($post_tags = wp_get_post_tags($post_id, array('fields' => 'ids'))) {

	// 		$post_tag_names = $tag_subscribedusers = $previoustags_subscribers = array();
	// 		foreach ($post_tags as $tag_id) {

	// 			$tag = get_tag($tag_id);
	// 			$post_tag_names[] = $tag->name;
				
	// 			// Get all the users who subscribed to each tag
	// 			if ($tag_subscribers = GD_MetaManager::get_term_meta($tag_id, GD_METAKEY_TERM_SUBSCRIBEDBY)) {

	// 				// Keep only the users with the corresponding preference on
	// 				$tag_subscribers = PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST, $tag_subscribers);

	// 				// From those, remove all users who got an email in a previous email function
	// 				$tag_subscribers = array_diff($tag_subscribers, $this->sent_emails[POP_EMAIL_CREATEDPOST]);
					
	// 				// Also remove all users who are subscribed to a previous tag
	// 				if ($tag_subscribers = array_diff($tag_subscribers, $previoustags_subscribers)) {

	// 					$tag_subscribedusers[$tag_id] = $tag_subscribers;
	// 					$previoustags_subscribers = array_merge(
	// 						$previoustags_subscribers,
	// 						$tag_subscribers
	// 					);
	// 				}
	// 			}
	// 		}

	// 		// Send the email to the subscribed users
	// 		if ($tag_subscribedusers) {

	// 			// No need to check if the post_status is "published", since it's been checked in the previous 2 functions (create/update)
	// 			$post_html = PoP_EmailTemplates_Factory::get_instance()->get_posthtml($post_id);
	// 			$post_name = gd_get_postname($post_id);
	// 			$post_title = get_the_title($post_id);
	// 			$footer = $this->get_preferences_footer(__('You are receiving this notification for having subscribed to tags/topics added in this post.', 'pop-emailsender'));

	// 			$authors = gd_get_postauthors($post_id);
	// 			$author = $authors[0];
	// 			$author_name = get_the_author_meta('display_name', $author);
	// 			$author_url = get_author_posts_url($author);
				
	// 			foreach ($tag_subscribedusers as $tag_id => $subscribedusers) {

	// 				$emails = $names = array();
	// 				foreach ($subscribedusers as $subscribeduser) {

	// 					$emails[] = get_the_author_meta('user_email', $subscribeduser);
	// 					$names[] = get_the_author_meta('display_name', $subscribeduser);
	// 				}

	// 				$tag = get_tag($tag_id);
	// 				$subject = sprintf(
	// 					__('There is a new %s with tag “%s”: “%s”', 'pop-emailsender'), 
	// 					$post_name,
	// 					$tag->name,
	// 					$post_title
	// 				);
					
	// 				$content = sprintf( 
	// 					'<p>%s</p>', 
	// 					sprintf(
	// 						__('<b><a href="%s">%s</a></b> has created a new %s, tagged with <b><i>%s</i></b>:', 'pop-emailsender'), 
	// 						$author_url,
	// 						$author_name,
	// 						$post_name,
	// 						implode(__(', ', 'pop-emailsender'), $post_tag_names)
	// 					)
	// 				);
	// 				$content .= $post_html;
	// 				$content .= $footer;

	// 				PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content, true);

	// 				// Add the users to the list of users who got an email sent to
	// 				$this->sent_emails[POP_EMAIL_CREATEDPOST] = array_merge(
	// 					$this->sent_emails[POP_EMAIL_CREATEDPOST],
	// 					$subscribedusers
	// 				);
	// 			}
	// 		}
	// 	}
	// }

	/**---------------------------------------------------------------------------------------------------------------
	 * General (all users): post created
	 * ---------------------------------------------------------------------------------------------------------------*/
	// Send an email to all users when a post is published
	function emailnotifications_general_newpost_create($post_id) {
		
		// Send email if the created post has been published
		if (get_post_status($post_id) == 'publish') {
			$this->sendemail_emailnotifications_general_newpost($post_id);
		}
	}
	function emailnotifications_general_newpost_update($post_id, $atts, $log) {
		
		// Send an email to all users when a post is published
		$old_status = $log['previous-status'];

		// Send email if the updated post has been published
		if (get_post_status($post_id) == 'publish' && $old_status != 'publish') {
			$this->sendemail_emailnotifications_general_newpost($post_id);
		}
	}
	protected function sendemail_emailnotifications_general_newpost($post_id) {

		// Check if for a given type of post the email must not be sent (eg: Highlights)
		if (PoP_EmailSender_Utils::sendemail_skip($post_id)) {
			return;
		}

		// Keep only the users with the corresponding preference on
		// Do not send to the current user
		$users = PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_GENERAL_NEWPOST, array(), array(get_current_user_id()));
		if ($users) {

			// From those, remove all users who got an email in a previous email function
			if ($users = array_diff($users, $this->sent_emails[POP_EMAIL_CREATEDPOST])) {

				$emails = $names = array();
				foreach ($users as $user) {

					$emails[] = get_the_author_meta( 'user_email', $user );
					$names[] = get_the_author_meta( 'display_name', $user );
				}

				// No need to check if the post_status is "published", since it's been checked in the previous 2 functions (create/update)
				$post_html = PoP_EmailTemplates_Factory::get_instance()->get_posthtml($post_id);
				$post_name = gd_get_postname($post_id);
				$post_title = get_the_title($post_id);
				$footer = $this->get_preferences_footer(__('You are currently receiving notifications for all new content posted on the website.', 'pop-emailsender'));

				$post = get_post($post_id);
				$author = $post->post_author;
				$author_name = get_the_author_meta('display_name', $author);
				$author_url = get_author_posts_url($author);
				$subject = sprintf(
					__('There is a new %s: “%s”', 'pop-emailsender'), 
					$post_name,
					$post_title
				);
				$content = sprintf( 
					'<p>%s</p>', 
					sprintf(
						__('<b><a href="%s">%s</a></b> has created a new %s:', 'pop-emailsender'), 
						$author_url,
						$author_name,
						$post_name
					)
				);
				$content .= $post_html;
				$content .= $footer;

				PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content, true);

				// Add the users to the list of users who got an email sent to
				$this->sent_emails[POP_EMAIL_CREATEDPOST] = array_merge(
					$this->sent_emails[POP_EMAIL_CREATEDPOST],
					$users
				);
			}
		}
	}

	function emailnotifications_network_addedcomment($comment_id, $comment) {

		$comment = get_comment($comment_id);

		// Only for published comments
		if ($comment->comment_approved != "1") {
			return;
		}

		// Get all the author's network's users (followers + members of same communities)
		$networkusers = get_user_networkusers($comment->user_id);
		if ($networkusers = array_diff($networkusers, $this->sent_emails[POP_EMAIL_ADDEDCOMMENT])) {

			// Keep only the users with the corresponding preference on
			if ($networkusers = PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT, $networkusers)) {

				$emails = $names = array();
				foreach ($networkusers as $networkuser) {

					$emails[] = get_the_author_meta( 'user_email', $networkuser );
					$names[] = get_the_author_meta( 'display_name', $networkuser );
				}

				$title = get_the_title($comment->comment_post_ID);
				$url = get_permalink($comment->comment_post_ID);
				$post_name = gd_get_postname($comment->comment_post_ID, 'lc');
				$author_name = get_the_author_meta('display_name', $comment->user_id);

				$content = sprintf( 
					__( '<p><a href="%1$s">%2$s</a> added a comment in %3$s <a href="%4%s">%5$s</a>:</p>', 'pop-emailsender'),
					get_author_posts_url($comment->user_id),
					$author_name,
					$post_name,
					$url,
					$title
				);

				$content .= PoP_EmailTemplates_Factory::get_instance()->get_commentcontenthtml($comment);

				$footer = $this->get_preferences_footer(__('You are receiving this notification for belonging to the authors’ network.', 'pop-emailsender'));
				$content .= $footer;
				
				// Possibly the title has html entities, these must be transformed again for the subjects below
				$title = html_entity_decode($title);

				$subject = sprintf( 
					__( '%1$s added a comment in %2$s “%3$s”', 'pop-emailsender'),
					$author_name,
					$post_name,
					$title
				);

				PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content, true);

				// Add the users to the list of users who got an email sent to
				$this->sent_emails[POP_EMAIL_ADDEDCOMMENT] = array_merge(
					$this->sent_emails[POP_EMAIL_ADDEDCOMMENT],
					$networkusers
				);
			}			
		}
	}

	function emailnotifications_subscribedtopic_addedcomment($comment_id, $comment) {

		// Only for published comments
		if ($comment->comment_approved != "1") {
			return;
		}

		$post_id = $comment->comment_post_ID;

		// If the post has tags...
		if ($post_tags = wp_get_post_tags($post_id, array('fields' => 'ids'))) {

			$post_html = PoP_EmailTemplates_Factory::get_instance()->get_posthtml($post_id);
			$post_name = gd_get_postname($post_id);
			$post_title = get_the_title($post_id);
			$footer = $this->get_preferences_footer(__('You are receiving this notification for having subscribed to tags/topics added in this comment/post.', 'pop-emailsender'));

			foreach ($post_tags as $tag_id) {

				// Get all the users who subscribed to each tag
				if ($tag_subscribers = GD_MetaManager::get_term_meta($tag_id, GD_METAKEY_TERM_SUBSCRIBEDBY)) {

					// From those, remove all users who got an email in a previous email function
					if ($tag_subscribers = array_diff($tag_subscribers, $this->sent_emails[POP_EMAIL_ADDEDCOMMENT])) {

						// Keep only the users with the corresponding preference on
						// Do not send to the current user
						if ($tag_subscribers = PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT, $tag_subscribers, array(get_current_user_id()))) {

							$emails = $names = array();
							foreach ($tag_subscribers as $tag_subscriber) {

								$emails[] = get_the_author_meta('user_email', $tag_subscriber);
								$names[] = get_the_author_meta('display_name', $tag_subscriber);
							}

							$tag = get_tag($tag_id);
							$tag_url = get_tag_link($tag_id);
							$tag_name = PoP_TagUtils::get_tag_symbol().$tag->name;
							$subject = sprintf(
								__('New comment added under %s with topic “%s”', 'pop-emailsender'), 
								$post_name,
								$tag_name
							);
							
							$content = sprintf( 
								'<p>%s</p>', 
								sprintf(
									__('A new comment has been added in %s <a href="%s">%s</a>, which has topic <a href="%s">%s</a>:', 'pop-emailsender'), 
									$post_name,
									get_permalink($post_id),
									$post_title,
									$tag_url,
									$tag_name
								)
							);
							$content .= $post_html;
							$content .= $footer;

							PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content, true);

							// Add the users to the list of users who got an email sent to
							$this->sent_emails[POP_EMAIL_ADDEDCOMMENT] = array_merge(
								$this->sent_emails[POP_EMAIL_ADDEDCOMMENT],
								$tag_subscribers
							);
						}
					}
				}
			}
		}
	}
	function emailnotifications_network_followeduser($target_id) {

		$user_id = get_current_user_id();

		// Get the current user's network's users (followers + members of same communities)
		$networkusers = get_user_networkusers($user_id);
		if ($networkusers = array_diff($networkusers, $this->sent_emails[POP_EMAIL_FOLLOWSUSER])) {

			// Keep only the users with the corresponding preference on
			if ($networkusers = PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER, $networkusers)) {

				$emails = $names = array();
				foreach ($networkusers as $networkuser) {

					$emails[] = get_the_author_meta( 'user_email', $networkuser );
					$names[] = get_the_author_meta( 'display_name', $networkuser );
				}

				$user_url = get_author_posts_url($user_id);
				$user_name = get_the_author_meta( 'display_name', $user_id);
				$target_name = get_the_author_meta( 'display_name', $target_id);
				$subject = sprintf(
					__( '%s is now following %s', 'pop-emailsender'), 
					$user_name,
					$target_name
				);
				
				$content = sprintf(
					__( '<p><a href="%s">%s</a> is now following:</p>', 'pop-emailsender'),
					$user_url,
					$user_name
				);
				$target_html = PoP_EmailTemplates_Factory::get_instance()->get_userhtml($target_id);
				$content .= $target_html;

				$footer = $this->get_preferences_footer(__('You are receiving this notification for belonging to the authors’ network.', 'pop-emailsender'));
				$content .= $footer;
				
				PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content);

				// Add the users to the list of users who got an email sent to
				$this->sent_emails[POP_EMAIL_FOLLOWSUSER] = array_merge(
					$this->sent_emails[POP_EMAIL_FOLLOWSUSER],
					$networkusers
				);
			}			
		}
	}
	function emailnotifications_network_recommendedpost($post_id) {

		$user_id = get_current_user_id();

		// Get the current user's network's users (followers + members of same communities)
		$networkusers = get_user_networkusers($user_id);
		if ($networkusers = array_diff($networkusers, $this->sent_emails[POP_EMAIL_RECOMMENDSPOST])) {

			// Keep only the users with the corresponding preference on
			if ($networkusers = PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST, $networkusers)) {

				$emails = $names = array();
				foreach ($networkusers as $networkuser) {

					$emails[] = get_the_author_meta( 'user_email', $networkuser );
					$names[] = get_the_author_meta( 'display_name', $networkuser );
				}

				$user_url = get_author_posts_url($user_id);
				$user_name = get_the_author_meta( 'display_name', $user_id);
				$post_title = get_the_title($post_id);
				$subject = sprintf(
					__( '%s has recommended “%s”', 'pop-emailsender'), 
					$user_name,
					$post_title
				);
				
				$content = sprintf(
					__( '<p><a href="%s">%s</a> has recommended:</p>', 'pop-emailsender'),
					$user_url,
					$user_name
				);
				$post_html = PoP_EmailTemplates_Factory::get_instance()->get_posthtml($post_id);
				$content .= $post_html;

				$footer = $this->get_preferences_footer(__('You are receiving this notification for belonging to the authors’ network.', 'pop-emailsender'));
				$content .= $footer;
				
				PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content);

				// Add the users to the list of users who got an email sent to
				$this->sent_emails[POP_EMAIL_RECOMMENDSPOST] = array_merge(
					$this->sent_emails[POP_EMAIL_RECOMMENDSPOST],
					$networkusers
				);
			}			
		}
	}
	function emailnotifications_network_subscribedtotopic($tag_id) {

		$user_id = get_current_user_id();

		// Get the current user's network's users (followers + members of same communities)
		$networkusers = get_user_networkusers($user_id);
		if ($networkusers = array_diff($networkusers, $this->sent_emails[POP_EMAIL_SUBSCRIBEDTOTOPIC])) {

			// Keep only the users with the corresponding preference on
			if ($networkusers = PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC, $networkusers)) {

				$emails = $names = array();
				foreach ($networkusers as $networkuser) {

					$emails[] = get_the_author_meta( 'user_email', $networkuser );
					$names[] = get_the_author_meta( 'display_name', $networkuser );
				}

				$user_url = get_author_posts_url($user_id);
				$user_name = get_the_author_meta( 'display_name', $user_id);
				$tag = get_tag($tag_id);
				$tag_name = PoP_TagUtils::get_tag_symbol().$tag->name;
				$subject = sprintf(
					__( '%s subscribed to %s', 'pop-emailsender'), 
					$user_name,
					$tag_name
				);
				
				$content = sprintf(
					__( '<p><a href="%s">%s</a> subscribed to:</p>', 'pop-emailsender'),
					$user_url,
					$user_name
				);
				$tag_html = PoP_EmailTemplates_Factory::get_instance()->get_taghtml($tag_id);
				$content .= $tag_html;

				$footer = $this->get_preferences_footer(__('You are receiving this notification for belonging to the authors’ network.', 'pop-emailsender'));
				$content .= $footer;
				
				PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content);

				// Add the users to the list of users who got an email sent to
				$this->sent_emails[POP_EMAIL_SUBSCRIBEDTOTOPIC] = array_merge(
					$this->sent_emails[POP_EMAIL_SUBSCRIBEDTOTOPIC],
					$networkusers
				);
			}			
		}
	}
	function emailnotifications_network_upvotedpost($post_id) {

		$this->emailnotifications_network_updownvotedpost($post_id, true);
	}
	function emailnotifications_network_downvotedpost($post_id) {

		$this->emailnotifications_network_updownvotedpost($post_id, false);
	}
	protected function emailnotifications_network_updownvotedpost($post_id, $upvote) {

		$user_id = get_current_user_id();

		// Get the current user's network's users (followers + members of same communities)
		$networkusers = get_user_networkusers($user_id);
		if ($networkusers = array_diff($networkusers, $this->sent_emails[POP_EMAIL_UPDOWNVOTEDPOST])) {

			// Keep only the users with the corresponding preference on
			if ($networkusers = PoP_EmailSender_EmailNotificationUtils::get_prereferenceon_users(GD_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST, $networkusers)) {

				$emails = $names = array();
				foreach ($networkusers as $networkuser) {

					$emails[] = get_the_author_meta( 'user_email', $networkuser );
					$names[] = get_the_author_meta( 'display_name', $networkuser );
				}

				// No need to check if the post_status is "published", since it's been checked in the previous 2 functions (create/update)
				$post_html = PoP_EmailTemplates_Factory::get_instance()->get_posthtml($post_id);
				$post_name = gd_get_postname($post_id);
				$post_title = get_the_title($post_id);
				$footer = $this->get_preferences_footer(__('You are receiving this notification for belonging to the authors’ network.', 'pop-emailsender'));

				$authors = gd_get_postauthors($post_id);
				$author = $authors[0];
				$author_name = get_the_author_meta('display_name', $author);
				$author_url = get_author_posts_url($author);
				$subject = sprintf(
					$upvote ? __('%s upvoted “%s”', 'pop-emailsender') : __('%s downvoted “%s”', 'pop-emailsender'), 
					$author_name,
					html_entity_decode($post_title)
				);
				$content = sprintf( 
					'<p>%s</p>', 
					sprintf(
						$upvote ? __('<a href="%s">%s</a> upvoted:', 'pop-emailsender') : __('<a href="%s">%s</a> downvoted:', 'pop-emailsender'), 
						$author_url,
						$author_name
					)
				);
				$content .= $post_html;
				$content .= $footer;
				
				PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content);

				// Add the users to the list of users who got an email sent to
				$this->sent_emails[POP_EMAIL_UPDOWNVOTEDPOST] = array_merge(
					$this->sent_emails[POP_EMAIL_UPDOWNVOTEDPOST],
					$networkusers
				);
			}			
		}
	}
	// function emailnotifications_digests_daily_newcontent($post_id) {

	// }
	// function emailnotifications_digests_daily_upcomingevents	($post_id) {

	// }

	/**---------------------------------------------------------------------------------------------------------------
	 * Create / Update Post
	 * ---------------------------------------------------------------------------------------------------------------*/
	// Send an email to all post owners when a post is created
	function sendemail_to_users_from_post_create($post_id) {

		// Check if for a given type of post the email must not be sent (eg: Highlights)
		if (PoP_EmailSender_Utils::sendemail_skip($post_id)) {
			return;
		}

		$status = get_post_status($post_id);
		
		$post_name = gd_get_postname($post_id);
		$subject = sprintf(__('Your %s was created successfully!', 'pop-emailsender'), $post_name);
		$content = ($status == 'publish') ? 
			sprintf( 
				'<p>%s</p>', 
				sprintf(
					__('Your %s was created successfully!', 'pop-emailsender'), 
					$post_name
				)
			) :
			sprintf( 
				__( '<p>Your %s <a href="%s">%s</a> was created successfully!</p>', 'pop-emailsender'), 
				$post_name,
				get_edit_post_link($post_id),
				get_the_title($post_id)
			);

		if ($status == 'publish') {

			$content .= PoP_EmailTemplates_Factory::get_instance()->get_posthtml($post_id);
		}
		elseif ($status == 'draft') {

			$content .= sprintf(
				'<p><em>%s</em></p>', 
				sprintf(
					// __('Please notice that the status of the %s is still <strong>\'Draft\'</strong>, it must be changed to <strong>\'Finished editing\'</strong> to have the website admins publish it.', 'pop-emailsender'), 
					__('Please notice that the status of the %s is <strong>\'Draft\'</strong>, so it won\'t be published online.', 'pop-emailsender'), 
					$post_name
				)
			);
		}
		elseif ($status == 'pending') {

			$content .= __('<p>Please wait for our moderators approval. You will receive an email with the confirmation.</p>');
		}

		$authors = PoP_EmailSender_Utils::sendemail_to_users_from_post($post_id, $subject, $content, $this->sent_emails[POP_EMAIL_CREATEDPOST]);

		// Add the users to the list of users who got an email sent to
		$this->sent_emails[POP_EMAIL_CREATEDPOST] = array_merge(
			$this->sent_emails[POP_EMAIL_CREATEDPOST],
			$authors
		);
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Send email to admin when post created/updated
	 * ---------------------------------------------------------------------------------------------------------------*/
	// Send an email to the admin also. // Copied from WPUF
	function sendemail_to_admin_createpost( $post_id ) {

		$this->sendemail_to_admin_createupdatepost($post_id, 'create');
	}
	function sendemail_to_admin_updatepost( $post_id ) {

		$this->sendemail_to_admin_createupdatepost($post_id, 'update');
	}
	protected function sendemail_to_admin_createupdatepost( $post_id, $type ) {

		$blogname = get_bloginfo( 'name' );
		$to = PoP_EmailSender_Utils::get_admin_notifications_email();
		$permalink = get_permalink( $post_id );
		$post_name = gd_get_postname($post_id);
		$post_author_id = get_post_field( 'post_author', $post_id );
		$author_name = get_the_author_meta('display_name', $post_author_id);
		$author_email = get_the_author_meta('user_email', $post_author_id);

		if ($type == 'create') {
			$subject = sprintf(
				__('[%s]: New %s by %s', 'pop-emailsender'), 
				$blogname, 
				$post_name, 
				$author_name
			);
			$msg = sprintf(
				__('A new %s has been submitted on %s:', 'pop-emailsender'), 
				$post_name, 
				$blogname
			);
		}
		elseif ($type == 'update') {
			$subject = sprintf(
				__('[%s]: %s updated by %s', 'pop-emailsender'), 
				$blogname, 
				$post_name, 
				$author_name 
			);
			$msg = sprintf(
				__('%s updated on %s:', 'pop-emailsender'), 
				$post_name, 
				$blogname
			);
		}

		$msg .= "<br/><br/>";
		$msg .= sprintf( 
			__('<b>Author:</b> %s', 'pop-emailsender'), 
			$author_name 
		) . "<br/>";
		$msg .= sprintf( 
			__('<b>Author Email:</b> <a href="mailto:%1$s">%1$s</a>', 'pop-emailsender'), 
			$author_email
		) . "<br/>";
		$msg .= sprintf(
			__('<b>Title:</b> %s', 'pop-emailsender'), 
			get_the_title($post_id)
		) . "<br/>";
		$msg .= sprintf(
			__('<b>Permalink:</b> <a href="%1$s">%1$s</a>', 'pop-emailsender'), 
			$permalink 
		) . "<br/>";
		$msg .= sprintf(
			__('<b>Edit Link:</b> <a href="%1$s">%1$s</a>', 'pop-emailsender'), 
			admin_url('post.php?action=edit&post='.$post_id)
		) . "<br/>";
		$msg .= sprintf(
			__('<b>Status:</b> %s', 'pop-emailsender'), 
			get_post_status($post_id)
		);

		PoP_EmailSender_Utils::send_email($to, $subject, $msg);
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Send email to admin when user created/updated
	 * ---------------------------------------------------------------------------------------------------------------*/
	function sendemail_to_admin_createuser( $user_id ) {

		// Send an email to the admin.
		$this->sendemail_to_admin_createupdateuser($user_id, 'create');
	}
	function sendemail_to_admin_updateuser( $user_id ) {

		$this->sendemail_to_admin_createupdateuser($user_id, 'update');
	}
	protected function sendemail_to_admin_createupdateuser( $user_id, $type ) {

		$blogname = get_bloginfo( 'name' );
		$to = PoP_EmailSender_Utils::get_admin_notifications_email();
		$permalink = get_author_posts_url( $user_id );
		$user_name = get_the_author_meta('display_name', $user_id);

		if ($type == 'create') {
			$subject = sprintf( __( '[%s]: New Profile: %s', 'pop-emailsender' ), $blogname, $user_name );
			$msg = sprintf( __( 'A Profile was created on %s.', 'pop-emailsender' ), $blogname );
		}
		elseif ($type == 'update') {
			$subject = sprintf( __( '[%s]: Profile updated: %s', 'pop-emailsender' ), $blogname, $user_name );
			$msg = sprintf( __( 'Profile updated on %s.', 'pop-emailsender' ), $blogname );
		}

		$msg .= "<br/><br/>";
		$msg .= sprintf( __( '<b>Profile:</b> %s', 'pop-emailsender' ), $user_name ) . "<br/>";
		$msg .= sprintf( __( '<b>Profile link:</b> <a href="%1$s">%1$s</a>', 'pop-emailsender' ), $permalink );

		PoP_EmailSender_Utils::send_email($to, $subject, $msg);
	}
	/**---------------------------------------------------------------------------------------------------------------
	 * Send the welcome email to the user
	 * ---------------------------------------------------------------------------------------------------------------*/
	function sendemail_userwelcome($user_id) {

		$blogname = get_bloginfo( 'name' );
		$subject = sprintf(
			__('Welcome to %s!', 'pop-emailsender'), 
			$blogname
		);
		$msg = sprintf(
			'<h1>%s</h1>', 
			$subject
		);
		$msg .= __('<p>Your user account was created successfully. This is your public profile page:</p>', 'pop-emailsender');
		$msg .= PoP_EmailTemplates_Factory::get_instance()->get_userhtml($user_id);

		if ($pages = apply_filters('sendemail_userwelcome:create_pages', array())) {

			$msg .= sprintf(
				'<br/><p>%s</p>',
				__('Now you can share your content/activities with our community:', 'pop-emailsender')
			);
			$msg .= '<ul>';
			foreach ($pages as $createpage) {

				// Allow values to be false, then don't show
				if ($createpage) {
					$msg .= sprintf(
						'<li><a href="%s">%s</a></li>',
						get_permalink($createpage),
						get_the_title($createpage)
					);
				}
			}
			$msg .= '</ul>';
		}
		
		$msg .= '<br/>';
		$msg .= sprintf(
			'<h2>%s</h2>', 
			sprintf(
				__('About %s', 'pop-emailsender'),
				$blogname
			)
		);
		$msg .= sprintf(
			'<p>%s</p>',
			gd_get_website_description()
		);

		PoP_EmailSender_Utils::sendemail_to_user($user_id, $subject, $msg);
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Send Email when post is approved
	 * ---------------------------------------------------------------------------------------------------------------*/
	function sendemail_to_users_from_post_referencesupdate($post_id, $atts, $log) {

		$old_status = $log['previous-status'];

		// Send email if the updated post has been published
		if (get_post_status($post_id) == 'publish' && $old_status != 'publish') {
			$this->sendemail_to_users_from_post_references($post_id);
		}
	}
	function sendemail_to_users_from_post_referencescreate($post_id) {

		// Send email if the created post has been published
		if (get_post_status($post_id) == 'publish') {
			$this->sendemail_to_users_from_post_references($post_id);
		}
	}
	function sendemail_to_users_from_post_referencestransition($post) {

		$this->sendemail_to_users_from_post_references($post->ID);
	}
	protected function sendemail_to_users_from_post_references($post_id) {

		// Check if for a given type of post the email must not be sent (eg: Highlights)
		if (apply_filters('post_references:skip_sendemail', false, $post_id)) {
			return;
		}

		// Check if the post has references. If so, also send email to the owners of those
		if ($references = GD_MetaManager::get_post_meta($post_id, GD_METAKEY_POST_REFERENCES)) {

			$post_name = gd_get_postname($post_id);
			$url = get_permalink($post_id);
			$title = get_the_title($post_id);
			$post_html = PoP_EmailTemplates_Factory::get_instance()->get_posthtml($post_id);

			// Get the name of the poster
			$post_author_id = get_post_field( 'post_author', $post_id );
			$author_name = get_the_author_meta('display_name', $post_author_id);

			foreach ($references as $reference_post_id) {

				$reference_post_name = gd_get_postname($reference_post_id);
				$reference_url = get_permalink($reference_post_id);
				$reference_title = get_the_title($reference_post_id);

				$reference_subject = sprintf( 
					__( 'A new %s was posted referencing "%s"', 'pop-emailsender' ), 
					$post_name, 
					$reference_title 
				);
				$reference_content = sprintf( 
					__( '<p>Your %s <a href="%s">%s</a> has been referenced by a new %s:</p>', 'pop-emailsender'), 
					$reference_post_name,
					$reference_url,
					$reference_title,
					$post_name
				);
				$reference_content .= $post_html;

				$authors = PoP_EmailSender_Utils::sendemail_to_users_from_post($reference_post_id, $reference_subject, $reference_content, $this->sent_emails[POP_EMAIL_CREATEDPOST]);	

				// Add the users to the list of users who got an email sent to
				$this->sent_emails[POP_EMAIL_CREATEDPOST] = array_merge(
					$this->sent_emails[POP_EMAIL_CREATEDPOST],
					$authors
				);
			}
		}
	}

	function sendemail_to_users_from_post_postapproved( $post ) {

		$post_id = $post->ID;

		$post_name = gd_get_postname($post_id);
		$url = get_permalink($post_id);
		$title = get_the_title($post_id);
		$post_html = PoP_EmailTemplates_Factory::get_instance()->get_posthtml($post_id);

		$subject = sprintf( __( 'Your %s was approved!', 'pop-emailsender' ), $post_name );
		$content = sprintf( 
			__( '<p>Hurray! Your %s was approved!</p>', 'pop-emailsender'), 
			$post_name
		);
		$content .= $post_html;

		$authors = PoP_EmailSender_Utils::sendemail_to_users_from_post($post_id, $subject, $content, $this->sent_emails[POP_EMAIL_CREATEDPOST]);

		// Add the users to the list of users who got an email sent to
		$this->sent_emails[POP_EMAIL_CREATEDPOST] = array_merge(
			$this->sent_emails[POP_EMAIL_CREATEDPOST],
			$authors
		);
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Send Email when adding comments
	 * ---------------------------------------------------------------------------------------------------------------*/
	function sendemail_to_users_from_comment( $comment_id, $comment ) {

		// If it is a trackback or a pingback, then do nothing
		// Only for published comments
		$skip = array(
			'pingback',
			'trackback'
		);
		if (in_array($comment->comment_type, $skip) || $comment->comment_approved != "1") {
			return;
		}

		// $post_id = $comment->comment_post_ID;
		// $title = get_the_title($post_id);
		// $url = get_permalink($post_id);

		// $is_response = false;
		// if ($comment->comment_parent) {
		// 	$parent = get_comment($comment->comment_parent);
		// 	$is_response = true;
		// }

		// $intro = $is_response ?
		// 	__( '<p>There is a response to a comment from <a href="%s">%s</a>:</p>', 'pop-emailsender') :
		// 	__( '<p>A new comment has been added to <a href="%s">%s</a>:</p>', 'pop-emailsender');

		// $content = sprintf( 
		// 	$intro,
		// 	$url,
		// 	$title
		// );
		
		// $content .= PoP_EmailTemplates_Factory::get_instance()->get_commenthtml($comment);
		// $content .= '<br/>';
		// if ($parent) {
			
		// 	$content .= sprintf(
		// 		'<em>%s</em>',
		// 		__('In response to:', 'pop-emailsender')
		// 	);
		// 	$content .= PoP_EmailTemplates_Factory::get_instance()->get_commenthtml($parent);
		// 	$content .= '<br/>';
		// }

		// $btn_title = __( 'Click here to reply the comment', 'pop-emailsender');
		// $content .= PoP_EmailTemplates_Factory::get_instance()->get_buttonhtml($btn_title, $url);
		
		$content = PoP_EmailTemplates_Factory::get_instance()->get_commentcontenthtml($comment);
		$post_id = $comment->comment_post_ID;
		// Possibly the title has html entities, these must be transformed again for the subjects below
		$title = html_entity_decode(get_the_title($post_id));

		// If this comment is a response, notify the original comment's author
		// Unless they are the same person
		if ($comment->comment_parent) {

			$parent = get_comment($comment->comment_parent);
			if ($parent->user_id != $comment->user_id) {

				$subject = sprintf( 
					__( '%s replied your comment in “%s”', 'pop-emailsender' ), 
					$comment->comment_author, 
					$title 
				);
				PoP_EmailSender_Utils::sendemail_to_users(array($parent->comment_author_email), array($parent->comment_author), $subject, $content);

				// Add the users to the list of users who got an email sent to
				$this->sent_emails[POP_EMAIL_ADDEDCOMMENT] = array_merge(
					$this->sent_emails[POP_EMAIL_ADDEDCOMMENT],
					array(
						$parent->user_id,
					)
				);
			}
		}

		// Send an email to:
		// 1. Owner(s) of the post
		$post_ids = array(
			$post_id
		);
		// 2. Owner(s) of referenced posts
		if ($references = GD_MetaManager::get_post_meta($post_id, GD_METAKEY_POST_REFERENCES)) {
			$post_ids = array_merge(
				$post_ids,
				$references
			);
		}
		// 3. Owner(s) of referencing posts
		if ($referencedby = PoPCore_Template_Processor_SectionBlocksUtils::get_referencedby($post_id)) {
			$post_ids = array_merge(
				$post_ids,
				$referencedby
			);	
		}

		$exclude_authors = $this->sent_emails[POP_EMAIL_ADDEDCOMMENT];

		// Do not send the email to the author of the comment
		$exclude_authors[] = $comment->user_id;

		// Do not send the email to the author of the parent comment, since we already sent it above
		if ($comment->comment_parent) {

			$exclude_authors[] = $parent->user_id;
		}
		$subject = sprintf( 
			__( 'New comment added in “%s”', 'pop-emailsender' ), 
			$title
		);
		
		$authors = PoP_EmailSender_Utils::sendemail_to_users_from_post($post_ids, $subject, $content, $exclude_authors);

		// Add the users to the list of users who got an email sent to
		$this->sent_emails[POP_EMAIL_ADDEDCOMMENT] = array_merge(
			$this->sent_emails[POP_EMAIL_ADDEDCOMMENT],
			$authors
		);
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Send Email when tagging a user in a post or comment
	 * ---------------------------------------------------------------------------------------------------------------*/
	function sendemail_to_taggedusers($taggedusers_ids, $subject, $content) {

		$emails = array();
		$names = array();
		foreach ($taggedusers_ids as $taggeduser_id) {
			
			$emails[] = get_the_author_meta( 'user_email', $taggeduser_id );
			$names[] = get_the_author_meta( 'display_name', $taggeduser_id );
		}

		PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content, true);
	}

	function sendemail_to_users_tagged_in_post($post_id, $taggedusers_ids, $newly_taggedusers_ids) {

		$post = get_post($post_id);

		// Only for published posts
		if ($post->post_status != 'publish') {
			return;
		}

		// 
		if ($newly_taggedusers_ids = array_diff($newly_taggedusers_ids, $this->sent_emails[POP_EMAIL_CREATEDPOST])) {

			$post_name = gd_get_postname($post_id, 'lc');

			$content = sprintf( 
				__( '<p><a href="%s">%s</a> mentioned you in %s:</p>', 'pop-emailsender'),
				get_author_posts_url($post->post_author),
				get_the_author_meta('display_name', $post->post_author),
				$post_name
			);

			$content .= PoP_EmailTemplates_Factory::get_instance()->get_posthtml($post_id);
			
			// Possibly the title has html entities, these must be transformed again for the subjects below
			$title = get_the_title($post_id);
			$title = html_entity_decode($title);

			$subject = sprintf( 
				__( 'You were mentioned in %1$s “%2$s”', 'pop-emailsender' ), 
				$post_name,
				$title
			);

			self::sendemail_to_taggedusers($newly_taggedusers_ids, $subject, $content);

			// Add the users to the list of users who got an email sent to
			$this->sent_emails[POP_EMAIL_CREATEDPOST] = array_merge(
				$this->sent_emails[POP_EMAIL_CREATEDPOST],
				$newly_taggedusers_ids
			);
		}
	}

	function sendemail_to_users_tagged_in_comment($comment_id, $taggedusers_ids) {

		$comment = get_comment($comment_id);

		// Only for published comments
		if ($comment->comment_approved != "1") {
			return;
		}

		// From those, remove all users who got an email in a previous email function
		if ($taggedusers_ids = array_diff($taggedusers_ids, $this->sent_emails[POP_EMAIL_ADDEDCOMMENT])) {

			$title = get_the_title($comment->comment_post_ID);
			$url = get_permalink($comment->comment_post_ID);
			$post_name = gd_get_postname($comment->comment_post_ID, 'lc');

			$content = sprintf( 
				__( '<p><a href="%1$s">%2$s</a> mentioned you in a comment from %3$s <a href="%4%s">%5$s</a>:</p>', 'pop-emailsender'),
				get_author_posts_url($comment->user_id),
				get_the_author_meta('display_name', $comment->user_id),
				$post_name,
				$url,
				$title
			);

			$content .= PoP_EmailTemplates_Factory::get_instance()->get_commentcontenthtml($comment);
			
			// Possibly the title has html entities, these must be transformed again for the subjects below
			$title = html_entity_decode($title);

			$subject = sprintf( 
				__( 'You were mentioned in a comment from %1$s “%2$s”', 'pop-emailsender' ), 
				$post_name,
				$title
			);

			self::sendemail_to_taggedusers($taggedusers_ids, $subject, $content);

			// Add the users to the list of users who got an email sent to
			$this->sent_emails[POP_EMAIL_ADDEDCOMMENT] = array_merge(
				$this->sent_emails[POP_EMAIL_ADDEDCOMMENT],
				$taggedusers_ids
			);
		}
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Follow user
	 * ---------------------------------------------------------------------------------------------------------------*/
	function followuser($target_id) {

		if (!in_array($target_id, $this->sent_emails[POP_EMAIL_FOLLOWSUSER])) {

			$user_id = get_current_user_id();
			$user_html = PoP_EmailTemplates_Factory::get_instance()->get_userhtml($user_id);
			
			$target_url = get_author_posts_url($target_id);
			$target_name = get_the_author_meta( 'display_name', $target_id);
			$subject = sprintf(__( 'You have a new follower!', 'pop-emailsender'), $target_name);
			
			$content = sprintf(
				__( '<p>Congratulations! <a href="%s">You have a new follower</a>:</p>', 'pop-emailsender'),
				GD_TemplateManager_Utils::add_tab($target_url, POP_COREPROCESSORS_PAGE_FOLLOWERS)
			);
			$content .= $user_html;

			$content .= '<br/>';
			$content .= __('<p>This user will receive notifications following your activity, such as recommending content, posting a new discussion or comment, and others.</p>', 'pop-emailsender');

			$email = get_the_author_meta('user_email', $target_id);	
			PoP_EmailSender_Utils::sendemail_to_users($email, $target_name, $subject, $content);

			// Add the users to the list of users who got an email sent to
			$this->sent_emails[POP_EMAIL_FOLLOWSUSER] = array_merge(
				$this->sent_emails[POP_EMAIL_FOLLOWSUSER],
				array($target_id)
			);
		}
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Recommend post
	 * ---------------------------------------------------------------------------------------------------------------*/
	function recommendpost($post_id) {

		$user_id = get_current_user_id();
		$user_html = PoP_EmailTemplates_Factory::get_instance()->get_userhtml($user_id);

		$post_name = gd_get_postname($post_id);
		$subject = sprintf(__( 'Your %s was recommended!'), $post_name);
		$content = sprintf( 
			__( '<p>Your %1$s <a href="%2$s">%3$s</a> was recommended by:</p>', 'pop-emailsender'), 
			$post_name,
			get_permalink($post_id),
			get_the_title($post_id)
		);
		$content .= $user_html;

		$authors = PoP_EmailSender_Utils::sendemail_to_users_from_post($post_id, $subject, $content, $this->sent_emails[POP_EMAIL_RECOMMENDSPOST]);

		// Add the users to the list of users who got an email sent to
		$this->sent_emails[POP_EMAIL_RECOMMENDSPOST] = array_merge(
			$this->sent_emails[POP_EMAIL_RECOMMENDSPOST],
			$authors
		);
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Hooks for all account-related stuff: login/logout/forgot pwd
	 * ---------------------------------------------------------------------------------------------------------------*/
	function retrieve_password_key() {

		add_filter('wp_mail_content_type', array($this, 'set_html_content_type'));
	}
	function set_html_content_type($content_type){
		return 'text/html';
	}
	function retrieve_password_title($title) {

		return __('Password reset code', 'pop-emailsender');
	}
	function retrievepasswordmessage($message, $key, $user_login, $user_data) {

		return PoP_EmailTemplates_Factory::get_instance()->add_emailframe(__('Retrieve password', 'pop-emailsender'), $message, $user_data->display_name);
	}
	function lostpasswordreset($user_id) {

		$subject = __('Password reset successful', 'pop-emailsender');
		$msg = sprintf(
			'<p>%s %s</p>',
			__('Your password has been changed successfully.', 'pop-emailsender'),
			sprintf(
				__('Please <a href="%s">click here to log-in</a>.', 'pop-emailsender'),
				wp_login_url()
			)
		);

		PoP_EmailSender_Utils::sendemail_to_user($user_id, $subject, $msg);
	}

	// Do not send an email when the user changes the password
	function donotsend($send) {

		// Returning in such a weird fashion, because on file wp-includes/user.php from WP 4.3.1 it validates like this:
		// if ( ! empty( $send_email_change_email ) ) {
		return array();
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_EmailSender_Hooks();

