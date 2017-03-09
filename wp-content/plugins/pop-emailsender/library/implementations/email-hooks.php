<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_EMAIL_MAIN', 'main');

class PoP_EmailSender_Hooks {

	// Needed to keep track of what users have an email sent to, to not duplicate email sending
	private $sent_emails;

	function __construct() {

		$this->sent_emails = array();

		add_action('gd_createupdate_post:update', array($this, 'sendemail_to_users_from_post_referencesupdate'), 10, 3);
		add_action('gd_createupdate_post:create', array($this, 'sendemail_to_users_from_post_referencescreate'), 10, 1);
		add_action('pending_to_publish', array($this, 'sendemail_to_users_from_post_referencestransition'), 10, 1);
		add_action('pending_to_publish', array($this, 'sendemail_to_users_from_post_postapproved'), 10, 1);
		add_action('wp_insert_comment', array($this, 'sendemail_to_users_from_comment'), 10, 2 );
		add_action('PoP_Mentions:post_tags:tagged_users', array($this, 'sendemail_to_users_tagged_in_post'), 10, 3);
		add_action('PoP_Mentions:comment_tags:tagged_users', array($this, 'sendemail_to_users_tagged_in_comment'), 10, 2);
		add_action('retrieve_password_key', array($this, 'retrieve_password_key'));
		add_filter('retrieve_password_title', 'retrieve_password_title');
		add_filter('retrieve_password_message', 'retrievepasswordmessage', 999999, 4);
		add_action('gd_lostpasswordreset', array($this, 'lostpasswordreset'), 10, 1);
		add_filter('send_password_change_email', array($this, 'donotsend'), 100000, 1);
		add_filter('send_email_change_email', array($this, 'donotsend'), 100000, 1);
		add_action('gd_createupdate_post:update', array($this, 'sendemail_to_usersnetwork_from_post_update'), 10, 3);
		add_action('gd_createupdate_post:create', array($this, 'sendemail_to_usersnetwork_from_post_create'), 10, 1);
		add_action('gd_createupdate_post:update', array($this, 'sendemail_to_subscribedtagusers_from_post_update'), 10, 3);
		add_action('gd_createupdate_post:create', array($this, 'sendemail_to_subscribedtagusers_from_post_create'), 10, 1);
		add_action('gd_followuser', array($this, 'followuser'), 10, 1);
		add_action('gd_recommendpost', array($this, 'recommendpost'), 10, 1);
		add_action('gd_createupdate_profile:additionals_create', array($this, 'sendemail_to_admin_createuser'), 100, 1);
		add_action('gd_createupdate_profile:additionals_create', array($this, 'sendemail_to_admin_createuser'), 100, 1);
		add_action('gd_createupdate_profile:additionals_update', array($this, 'sendemail_to_admin_updateuser'), 100, 1);
		add_action('gd_createupdate_post:update', array($this, 'sendemail_to_admin_updatepost'), 100, 1);
		add_action('gd_createupdate_post:create', array($this, 'sendemail_to_admin_createpost'), 100, 1);
		add_action('gd_createupdate_post:create', array($this, 'sendemail_to_users_from_post_create'), 100, 1);
	}

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

		PoP_EmailSender_Utils::sendemail_to_users_from_post($post_id, $subject, $content);
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

				PoP_EmailSender_Utils::sendemail_to_users_from_post($reference_post_id, $reference_subject, $reference_content);	
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

		PoP_EmailSender_Utils::sendemail_to_users_from_post($post_id, $subject, $content);
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Send Email when adding comments
	 * ---------------------------------------------------------------------------------------------------------------*/
	function sendemail_to_users_from_comment( $comment_id, $comment ) {

		// If it is a trackback or a pingback, then do nothing
		$skip = array(
			'pingback',
			'trackback'
		);
		if (in_array($comment->comment_type, $skip)) {
			return;
		}

		$post_id = $comment->comment_post_ID;
		$title = get_the_title($post_id);
		$url = get_permalink($post_id);

		$is_response = false;
		if ($comment->comment_parent) {
			$parent = get_comment($comment->comment_parent);
			$is_response = true;
		}

		$intro = $is_response ?
			__( '<p>There is a response to a comment from <a href="%s">%s</a>:</p>', 'pop-emailsender') :
			__( '<p>A new comment has been added to <a href="%s">%s</a>:</p>', 'pop-emailsender');

		$content = sprintf( 
			$intro,
			$url,
			$title
		);
		
		$content .= PoP_EmailTemplates_Factory::get_instance()->get_commenthtml($comment);
		$content .= '<br/>';
		if ($parent) {
			
			$content .= sprintf(
				'<em>%s</em>',
				__('In response to:', 'pop-emailsender')
			);
			$content .= PoP_EmailTemplates_Factory::get_instance()->get_commenthtml($parent);
			$content .= '<br/>';
		}

		$btn_title = __( 'Click here to reply the comment', 'pop-emailsender');
		$content .= PoP_EmailTemplates_Factory::get_instance()->get_buttonhtml($btn_title, $url);

		// Possibly the title has html entities, these must be transformed again for the subjects below
		$title = html_entity_decode($title);

		// If this comment is a response, notify the original comment's author
		// Unless they are the same person
		if ($parent) {

			if ($parent->user_id != $comment->user_id) {
				$subject = sprintf( 
					__( '%s replied your comment in “%s”', 'pop-emailsender' ), 
					$comment->comment_author, 
					$title 
				);
				PoP_EmailSender_Utils::sendemail_to_users(array($parent->comment_author_email), array($parent->comment_author), $subject, $content);
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

		// // 1. Send an email to the owner of the post
		// $authors = gd_get_postauthors($post_id);

		// $skip_email_to_postowner = 
		// 	// Do not send the email if the owner of the post is unique (not co-sharing authors) and is actually the user posting the comment
		// 	(count($authors) == 1 && $authors[0] == $comment->user_id) ||
		// 	// Send only if the owner is not the same with the author of the parent comment (so that user doesn't receive 2 emails!)
		// 	// (If it is the same, the email is already sent to this user with the logic above)
		// 	($parent && (count($authors) == 1 && $authors[0] == $parent->user_id));

		// if (!$skip_email_to_postowner) {

		// 	$post_ids[] = $post_id;
		// }

		$exclude_authors = array(
			$comment->user_id,
			$parent->user_id,
		);

		$subject = sprintf( 
			__( 'New comment added in “%s”', 'pop-emailsender' ), 
			$title
		);
		PoP_EmailSender_Utils::sendemail_to_users_from_post($post_ids, $subject, $content, $exclude_authors);
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
	}

	function sendemail_to_users_tagged_in_comment($comment_id, $taggedusers_ids) {

		$comment = get_comment($comment_id);

		// Only for published comments
		if ($comment->comment_approved != "1") {
			return;
		}

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

		$content .= PoP_EmailTemplates_Factory::get_instance()->get_commenthtml($comment);
		$content .= '<br/>';

		if ($comment->comment_parent) {

			$parent = get_comment($comment->comment_parent);
			
			$content .= sprintf(
				'<em>%s</em>',
				__('In response to:', 'pop-emailsender')
			);
			$content .= PoP_EmailTemplates_Factory::get_instance()->get_commenthtml($parent);
			$content .= '<br/>';
		}

		$btn_title = __( 'Click here to reply the comment', 'pop-emailsender');
		$content .= PoP_EmailTemplates_Factory::get_instance()->get_buttonhtml($btn_title, $url);
		
		// Possibly the title has html entities, these must be transformed again for the subjects below
		$title = html_entity_decode($title);

		$subject = sprintf( 
			__( 'You were mentioned in a comment from %1$s “%2$s”', 'pop-emailsender' ), 
			$post_name,
			$title
		);

		self::sendemail_to_taggedusers($taggedusers_ids, $subject, $content);
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


	/**---------------------------------------------------------------------------------------------------------------
	 * User's network notification emails: post created
	 * ---------------------------------------------------------------------------------------------------------------*/
	// Send an email to all post owners's network when a post is published
	function sendemail_to_usersnetwork_from_post_update($post_id, $atts, $log) {

		$old_status = $log['previous-status'];

		// Send email if the updated post has been published
		if (get_post_status($post_id) == 'publish' && $old_status != 'publish') {
			$this->sendemail_to_usersnetwork_from_post($post_id);
		}
	}
	function sendemail_to_usersnetwork_from_post_create($post_id) {

		// Send email if the created post has been published
		if (get_post_status($post_id) == 'publish') {
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
		$footer = sprintf(
			'<p><small>%s</small></p>',
			__('You are receiving this notification for belonging to this author’s network. Soon you will be able to configure your notification email’s preferences.', 'pop-emailsender')
		);
		$allnetworkusers = array();
		$authors = gd_get_postauthors($post_id);
		foreach ($authors as $author) {

			// Get all the author's network's users (followers + members of same communities)
			$networkusers = get_user_networkusers($author);

			// Do not send email to the authors of the post, they know already!
			$networkusers = array_diff($networkusers, $authors);

			// If post has co-authors, and these have the same follower, then do not send the same email to the follower for each co-author
			if ($networkusers = array_diff($networkusers, $allnetworkusers)) {

				$allnetworkusers = array_merge(
					$allnetworkusers,
					$networkusers
				);

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
			}			
		}

		// Add the users to the list of users who got an email sent to
		if (!$this->sent_emails[POP_EMAIL_MAIN]) {
			$this->sent_emails[POP_EMAIL_MAIN] = array();
		}
		$this->sent_emails[POP_EMAIL_MAIN] = array_merge(
			$this->sent_emails[POP_EMAIL_MAIN],
			$allnetworkusers
		);
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Subscribed tags/topics: post created
	 * ---------------------------------------------------------------------------------------------------------------*/
	// Send an email to all post owners's network when a post is published
	function sendemail_to_subscribedtagusers_from_post_update($post_id, $atts, $log) {

		$old_status = $log['previous-status'];

		// Send email if the updated post has been published
		if (get_post_status($post_id) == 'publish' && $old_status != 'publish') {
			$this->sendemail_to_subscribedtagusers_from_post($post_id);
		}
	}
	function sendemail_to_subscribedtagusers_from_post_create($post_id) {

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

		// Do not send for RIPESS
		if (!apply_filters('PoP_EmailSender_Hooks:sendemail_to_subscribedtagusers_from_post:enabled', true)) {
			return;
		}

		// If the post has tags...
		if ($post_tags = wp_get_post_tags($post_id, array('fields' => 'ids'))) {

			$post_tag_names = $tag_subscribedusers = $previoustags_subscribers = array();
			foreach ($post_tags as $tag_id) {

				$tag = get_tag($tag_id);
				$post_tag_names[] = $tag->name;
				
				// Get all the users who subscribed to each tag
				if ($tag_subscribers = GD_MetaManager::get_term_meta($tag_id, GD_METAKEY_TERM_SUBSCRIBEDBY)) {
				
					// From those, remove all users who got an email in previous email function, usersnetwork
					$tag_subscribers = array_diff($tag_subscribers, $this->sent_emails[POP_EMAIL_MAIN]);
					
					// Also remove all users who are subscribed to a previous tag
					$tag_subscribers = array_diff($tag_subscribers, $previoustags_subscribers);

					if ($tag_subscribers) {

						$tag_subscribedusers[$tag_id] = $tag_subscribers;
						$previoustags_subscribers = array_merge(
							$previoustags_subscribers,
							$tag_subscribers
						);
					}
				}
			}

			// Send the email to the subscribed users
			if ($tag_subscribedusers) {

				// Add the users to the list of users who got an email sent to
				if (!$this->sent_emails[POP_EMAIL_MAIN]) {
					$this->sent_emails[POP_EMAIL_MAIN] = array();
				}

				// No need to check if the post_status is "published", since it's been checked in the previous 2 functions (create/update)
				$post_html = PoP_EmailTemplates_Factory::get_instance()->get_posthtml($post_id);
				$post_name = gd_get_postname($post_id);
				$post_title = get_the_title($post_id);
				$footer = sprintf(
					'<p><small>%s</small></p>',
					__('You are receiving this notification for having subscribed to tags/topics added in this post. Soon you will be able to configure your notification email’s preferences.', 'pop-emailsender')
				);

				$authors = gd_get_postauthors($post_id);
				$author = $authors[0];
				$author_name = get_the_author_meta('display_name', $author);
				$author_url = get_author_posts_url($author);
				
				foreach ($tag_subscribedusers as $tag_id => $subscribedusers) {

					$emails = $names = array();
					foreach ($subscribedusers as $subscribeduser) {

						$emails[] = get_the_author_meta('user_email', $subscribeduser);
						$names[] = get_the_author_meta('display_name', $subscribeduser);
					}

					$tag = get_tag($tag_id);
					$subject = sprintf(
						__('There’s a new %s with tag “%s”: “%s”', 'pop-emailsender'), 
						$post_name,
						$tag->name,
						$post_title
					);
					
					$content = sprintf( 
						'<p>%s</p>', 
						sprintf(
							__('<b><a href="%s">%s</a></b> has created a new %s, tagged with <b><i>%s</i></b>:', 'pop-emailsender'), 
							$author_url,
							$author_name,
							$post_name,
							implode(__(', ', 'pop-emailsender'), $post_tag_names)
						)
					);
					$content .= $post_html;
					$content .= $footer;

					PoP_EmailSender_Utils::sendemail_to_users($emails, $names, $subject, $content, true);

					// Add the users to the list of users who got an email sent to
					$this->sent_emails[POP_EMAIL_MAIN] = array_merge(
						$this->sent_emails[POP_EMAIL_MAIN],
						$subscribedusers
					);
				}
			}
		}
	}

	/**---------------------------------------------------------------------------------------------------------------
	 * Follow user
	 * ---------------------------------------------------------------------------------------------------------------*/
	function followuser($target_id) {

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

		PoP_EmailSender_Utils::sendemail_to_users_from_post($post_id, $subject, $content);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_EmailSender_Hooks();

