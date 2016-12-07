<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EMAIL_TEMPLATE_EMAIL', 'email.html');
define ('GD_EMAIL_TEMPLATE_EMAILBODY', 'emailbody.html');
define ('GD_EMAIL_TEMPLATE_BUTTON', 'button.html');

function gd_sendemail_skip($post_id) {

	// Check if for a given type of post the email must not be sent (eg: Highlights)
	return apply_filters('create_post:skip_sendemail', false, $post_id);
}
function gd_email_notifications_email(){
	
	// By default, use the admin_email, but this can be overriden
	return apply_filters('gd_email_notifications_email', get_bloginfo( 'admin_email' ));
}
function gd_email_newsletter_email(){
	
	// By default, use the admin_email, but this can be overriden
	return apply_filters('gd_email_newsletter_email', get_bloginfo( 'admin_email' ));
}
function gd_email_info_email(){
	
	// By default, use the admin_email, but this can be overriden
	return apply_filters('gd_email_info_email', get_bloginfo( 'admin_email' ));
}

function set_html_content_type($content_type){
	return 'text/html';
}

function gd_email_template_folder() {
	return apply_filters('gd_sendemail_to_users:template_folder', '');
}

function gd_sendemail( $to, $subject, $msg ) {

	$blogname = get_bloginfo( 'name' );

	$from_email = gd_email_info_email();
	$headers = sprintf( "From: %s <%s>\r\n", $blogname, $from_email );
	$headers .= "Content-Type: text/html; charset=\"" . strtolower( get_option('blog_charset') ) . "\"\n";		

	// Allow to send HTML emails
	// add_filter('wp_mail_content_type','set_html_content_type');

	// Get rid of html code in the subject
	// $subject = wp_specialchars_decode($subject);
	$subject = html_entity_decode(wp_kses_data($subject)); //decode entities, but run kses first just in case users use placeholders containing html
	
	wp_mail( $to, $subject, $msg, $headers );

	// Remove filter as explained in http://codex.wordpress.org/Function_Reference/wp_mail
	// remove_filter('wp_mail_content_type','set_html_content_type');
}

// Cache the already generated frames for other users
global $email_frames;
$email_frames = array();
function gd_email_addframe($title, $content, $names = array(), $template = GD_EMAIL_TEMPLATE_EMAIL) {

	if ($email_template_folder = gd_email_template_folder()) {

		// If the frame had been generated, then fetch it from the cache
		global $email_frames;
		$template_filename = $email_template_folder . $template;
		if (!isset($email_frames[$template_filename])) {

			$url = trailingslashit(home_url());
			$footer = sprintf(
				'<p><a href="%s">%s</a><br/>%s</p>', 
				$url,
				get_bloginfo('name'),
				get_bloginfo('description')
			);

			ob_start();
			include ($template_filename);
			$email_frames[$template_filename] = str_replace(
				array('{{TITLE}}', '{{URL}}', '{{FOOTER}}'), 
				array($title, $url, $footer), 
				ob_get_clean()
			);
		}
		// Message
		if ($names) {
			$header = sprintf(__('<p>Dear %s,</p>', 'pop-coreprocessors'), implode(', ', $names));
		}
		else {
			$header = __('<p>Howdy!</p>', 'pop-coreprocessors');
		}
		$msg = str_replace('{{CONTENT}}', $header.$content, $email_frames[$template_filename]);
	}
	else {
		$msg = $content;
	}

	return $msg;
}

function gd_sendemail_to_users($emails, $names, $subject, $msg, $individual = true) {

	if (!is_array($emails)) {
		$emails = array($emails);
	}
	if ($names && !is_array($names)) {
		$names = array($names);
	}

	// When splitting, send individual emails to each author
	if ($individual) {

		for ($i=0; $i < count($emails); $i++) { 

			$to = $emails[$i];
			if ($names) {
				$name = array($names[$i]);
			}
			$emailmsg = gd_email_addframe($subject, $msg, $name);
			gd_sendemail($to, $subject, $emailmsg);
		}
	}
	else {

		$to = implode(',', $emails);	
		$emailmsg = gd_email_addframe($subject, $msg, $names);
		gd_sendemail($to, $subject, $emailmsg);
	}
}

function gd_sendemail_to_users_from_post($post_ids, $subject, $content, $exclude_authors = array()) {

	if (!is_array($post_ids)) {
		$post_ids = array($post_ids);
	}
	$emails = array();
	$names = array();

	// If authors are repeated along different post_ids, they will still receive only 1 email each
	$authors = array();
	foreach ($post_ids as $post_id) {
		$authors = array_merge(
			$authors,
			gd_get_postauthors($post_id)
		);
	}

	// Exclude authors
	$authors = array_diff($authors, $exclude_authors);
	foreach ($authors as $author) {
		
		$emails[] = get_the_author_meta( 'user_email', $author );
		$names[] = get_the_author_meta( 'display_name', $author );
	}

	gd_sendemail_to_users($emails, $names, $subject, $content, true);
}

/**---------------------------------------------------------------------------------------------------------------
 * Create / Update Post
 * ---------------------------------------------------------------------------------------------------------------*/

// Send an email to all post owners when a post is created
add_action('gd_createupdate_post:create', 'gd_sendemail_to_users_from_post_create', 100, 1);
function gd_sendemail_to_users_from_post_create($post_id) {

	// Check if for a given type of post the email must not be sent (eg: Highlights)
	if (gd_sendemail_skip($post_id)) {
		return;
	}

	$status = get_post_status($post_id);
	
	$post_name = gd_get_postname($post_id);
	$subject = sprintf(__('Your %s was created successfully!', 'pop-coreprocessors'), $post_name);
	$content = ($status == 'publish') ? 
		sprintf( 
			'<p>%s</p>', 
			sprintf(
				__('Your %s was created successfully!', 'pop-coreprocessors'), 
				$post_name
			)
		) :
		sprintf( 
			__( '<p>Your %s <a href="%s">%s</a> was created successfully!</p>', 'pop-coreprocessors'), 
			$post_name,
			get_edit_post_link($post_id),
			get_the_title($post_id)
		);

	if ($status == 'publish') {

		$content .= gd_sendemail_get_posthtml($post_id);
	}
	elseif ($status == 'draft') {

		$content .= sprintf(
			'<p><em>%s</em></p>', 
			sprintf(
				// __('Please notice that the status of the %s is still <strong>\'Draft\'</strong>, it must be changed to <strong>\'Finished editing\'</strong> to have the website admins publish it.', 'pop-coreprocessors'), 
				__('Please notice that the status of the %s is <strong>\'Draft\'</strong>, so it won\'t be published online.', 'pop-coreprocessors'), 
				$post_name
			)
		);
	}
	elseif ($status == 'pending') {

		$content .= __('<p>Please wait for our moderators approval. You will receive an email with the confirmation.</p>');
	}

	gd_sendemail_to_users_from_post($post_id, $subject, $content);
}

/**---------------------------------------------------------------------------------------------------------------
 * Send email to admin when post created/updated
 * ---------------------------------------------------------------------------------------------------------------*/

// Send an email to the admin also. // Copied from WPUF
add_action('gd_createupdate_post:create', 'gd_sendemail_to_admin_createpost', 100, 1);
function gd_sendemail_to_admin_createpost( $post_id ) {

	gd_sendemail_to_admin_createupdatepost($post_id, 'create');
}
add_action('gd_createupdate_post:update', 'gd_sendemail_to_admin_updatepost', 100, 1);
function gd_sendemail_to_admin_updatepost( $post_id ) {

	gd_sendemail_to_admin_createupdatepost($post_id, 'update');
}
function gd_sendemail_to_admin_createupdatepost( $post_id, $type ) {

	$blogname = get_bloginfo( 'name' );
	// $to = get_bloginfo( 'admin_email' );
	$to = gd_email_notifications_email();
	$permalink = get_permalink( $post_id );
	$post_name = gd_get_postname($post_id);
	$post_author_id = get_post_field( 'post_author', $post_id );
	$author_name = get_the_author_meta('display_name', $post_author_id);
	$author_email = get_the_author_meta('user_email', $post_author_id);

	// $headers = sprintf( "From: %s <%s>\r\n", $blogname, $to );
	// $headers .= "Content-Type: text/html; charset=\"" . strtolower( get_option('blog_charset') ) . "\"\n";		
	
	if ($type == 'create') {
		$subject = sprintf( __( '[%s]: New %s by %s' ), $blogname, $post_name, $author_name );
		$msg = sprintf( __( 'A new %s has been submitted on %s:' ), $post_name, $blogname );
	}
	elseif ($type == 'update') {
		$subject = sprintf( __( '[%s]: %s updated by %s' ), $blogname, $post_name, $author_name );
		$msg = sprintf( __( '%s updated on %s:' ), $post_name, $blogname );
	}

	$msg .= "<br/><br/>";
	$msg .= sprintf( __( '<b>Author:</b> %s' ), $author_name ) . "<br/>";
	$msg .= sprintf( __( '<b>Author Email:</b> <a href="mailto:%1$s">%1$s</a>' ), $author_email ) . "<br/>";
	$msg .= sprintf( __( '<b>Title:</b> %s' ), get_the_title( $post_id ) ) . "<br/>";
	$msg .= sprintf( __( '<b>Permalink:</b> <a href="%1$s">%1$s</a>' ), $permalink ) . "<br/>";
	$msg .= sprintf( __( '<b>Edit Link:</b> <a href="%1$s">%1$s</a>' ), admin_url( 'post.php?action=edit&post=' . $post_id ) ) . "<br/>";
	$msg .= sprintf( __( '<b>Status:</b> %s' ), get_post_status( $post_id ) );

	gd_sendemail( $to, $subject, $msg );

	// wp_mail( $to, $subject, $msg, $headers );
}

/**---------------------------------------------------------------------------------------------------------------
 * Send email to admin when user created/updated
 * ---------------------------------------------------------------------------------------------------------------*/

add_action('gd_createupdate_profile:additionals_create', 'gd_sendemail_to_admin_createuser', 100, 1);
function gd_sendemail_to_admin_createuser( $user_id ) {

	// Send an email to the admin.
	gd_sendemail_to_admin_createupdateuser($user_id, 'create');

	// Send the welcome email to the user
	gd_sendemail_userwelcome($user_id);
}
add_action('gd_createupdate_profile:additionals_update', 'gd_sendemail_to_admin_updateuser', 100, 1);
function gd_sendemail_to_admin_updateuser( $user_id ) {

	gd_sendemail_to_admin_createupdateuser($user_id, 'update');
}
function gd_sendemail_to_admin_createupdateuser( $user_id, $type ) {

	$blogname = get_bloginfo( 'name' );
	// $to = get_bloginfo( 'admin_email' );
	$to = gd_email_notifications_email();
	$permalink = get_author_posts_url( $user_id );
	$user_name = get_the_author_meta('display_name', $user_id);

	// $headers = sprintf( "From: %s <%s>\r\n", $blogname, $to );
	// $headers .= "Content-Type: text/html; charset=\"" . strtolower( get_option('blog_charset') ) . "\"\n";		
	
	if ($type == 'create') {
		$subject = sprintf( __( '[%s]: New Profile: %s' ), $blogname, $user_name );
		$msg = sprintf( __( 'A Profile was created on %s.' ), $blogname );
	}
	elseif ($type == 'update') {
		$subject = sprintf( __( '[%s]: Profile updated: %s' ), $blogname, $user_name );
		$msg = sprintf( __( 'Profile updated on %s.' ), $blogname );
	}

	$msg .= "<br/><br/>";
	$msg .= sprintf( __( '<b>Profile:</b> %s' ), $user_name ) . "<br/>";
	$msg .= sprintf( __( '<b>Profile link:</b> <a href="%1$s">%1$s</a>' ), $permalink );

	gd_sendemail( $to, $subject, $msg );
}
function gd_sendemail_userwelcome($user_id) {

	$blogname = get_bloginfo( 'name' );
	$subject = sprintf(
		__('Welcome to %s!', 'pop-coreprocessors'), 
		$blogname
	);
	$msg = sprintf(
		'<h1>%s</h1>', 
		$subject
	);
	$msg .= __('<p>Your user account was created successfully. This is your public profile page:</p>', 'pop-coreprocessors');
	$msg .= gd_sendemail_get_userhtml($user_id);

	if ($pages = apply_filters('sendemail_userwelcome:create_pages', array())) {

		$msg .= sprintf(
			'<br/><p>%s</p>',
			__('Now you can share your content/activities with our community:', 'pop-coreprocessors')
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
			__('About %s', 'pop-coreprocessors'),
			$blogname
		)
	);
	$msg .= sprintf(
		'<p>%s</p>',
		gd_get_website_description()
	);

	gd_sendemail_to_user($user_id, $subject, $msg);
}
function gd_sendemail_to_user($user_id, $subject, $msg) {

	$email = get_the_author_meta( 'user_email', $user_id );
	$name = get_the_author_meta('display_name', $user_id);
	
	gd_sendemail_to_users($email, $name, $subject, $msg);
}

function gd_sendemail_get_userhtml($user_id) {

	$author_url = get_author_posts_url($user_id);
	$author_name = get_the_author_meta( 'display_name', $user_id);
	$avatar = gd_get_avatar($user_id, GD_AVATAR_SIZE_60);
	$avatar_html = sprintf(
		'<a href="%1$s"><img src="%2$s" width="%3$s" height="%3$s"></a>',
		$author_url,
		$avatar['src'],
		$avatar['size']
	);
	$name_html = sprintf(
		'<h3 style="display: block;"><a href="%s">%s</a></h3>%s',
		$author_url,
		$author_name,
		gd_get_user_shortdescription($user_id)
	);

	$userhtml_styles = apply_filters('gd_sendemail_get_userhtml:userhtml_styles', array('width: 100%'));
	$user_html = sprintf(
		'<table cellpadding=10 cellspacing=0 border=0 style="%s">'.
			'<tr valign="top">'.
				'<td width="%s" valign="top">%s</td><td valign="top">%s</td>'
			.'</tr>'.
		'</table>',
		implode(';', $userhtml_styles),
		$avatar['size'],
		$avatar_html,
		$name_html
	);
	
	return $user_html;
}

function gd_sendemail_get_posthtml($post_id) {

	$post_url = get_permalink($post_id);
	$post_title = get_the_title($post_id);
	$thumb = wp_get_attachment_image_src(gd_get_thumb_id($post_id), 'thumb-sm');
	$thumb_html = sprintf(
		'<a href="%1$s"><img src="%2$s" width="%3$s" height="%4$s"></a>',
		$thumb_url,
		$thumb[0],
		$thumb[1],
		$thumb[2]
	);
	$title_html = sprintf(
		'<h3 style="display: block;"><a href="%s">%s</a></h3>',
		$post_url,
		$post_title
	);

	$posthtml_styles = apply_filters('gd_sendemail_get_userhtml:posthtml_styles', array('width: 100%'));
	$post_html = sprintf(
		'<table cellpadding=10 cellspacing=0 border=0 style="%s">'.
			'<tr valign="top">'.
				'<td width="%s" valign="top">%s</td><td valign="top">%s</td>'
			.'</tr>'.
		'</table>',
		implode(';', $posthtml_styles),
		$thumb[1],
		$thumb_html,
		$title_html
	);
	
	return $post_html;
}

function gd_sendemail_get_commenthtml($comment) {

	$avatar = gd_get_avatar($comment->user_id, GD_AVATAR_SIZE_40);
	$avatar_html = sprintf(
		'<a href="%1$s"><img src="%2$s" width="%3$s" height="%3$s"></a>',
		$comment->comment_author_url,
		$avatar['src'],
		$avatar['size']
	);
	
	$comment_styles = apply_filters('gd_sendemail_to_users_from_comment:comment_styles', array('width: 100%'));
	$comment_html = sprintf(
		'<table cellpadding=10 cellspacing=0 border=0 style="%s">'.
			'<tr valign="top">'.
				'<td width="%s" valign="top">%s</td><td valign="top"><a href="%s">%s</a>&nbsp;<small>%s</small><br/>%s</td>'
			.'</tr>'.
		'</table>',
		implode(';', $comment_styles),
		$avatar['size'],
		$avatar_html,
		$comment->comment_author_url,
		$comment->comment_author,
		mysql2date(get_option('date_format'), $comment->comment_date_gmt),
		gd_comments_apply_filters($comment->comment_content)
	);
	
	return $comment_html;
}

function gd_sendemail_get_websitehtml() {

	return sprintf(
		'<a href="%s">%s</a>',
		get_site_url(),
		get_bloginfo('name')
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * Send Email when post is approved
 * ---------------------------------------------------------------------------------------------------------------*/
add_action('gd_createupdate_post:update', 'gd_sendemail_to_users_from_post_referencesupdate', 10, 3);
function gd_sendemail_to_users_from_post_referencesupdate($post_id, $atts, $log) {

	$old_status = $log['previous-status'];

	// Send email if the updated post has been published
	if (get_post_status($post_id) == 'publish' && $old_status != 'publish') {
		gd_sendemail_to_users_from_post_references($post_id);
	}
}
add_action('gd_createupdate_post:create', 'gd_sendemail_to_users_from_post_referencescreate', 10, 1);
function gd_sendemail_to_users_from_post_referencescreate($post_id) {

	// Send email if the created post has been published
	if (get_post_status($post_id) == 'publish') {
		gd_sendemail_to_users_from_post_references($post_id);
	}
}
add_action('pending_to_publish', 'gd_sendemail_to_users_from_post_referencestransition', 10, 1);
function gd_sendemail_to_users_from_post_referencestransition($post) {

	gd_sendemail_to_users_from_post_references($post->ID);
}
function gd_sendemail_to_users_from_post_references($post_id) {

	// Check if for a given type of post the email must not be sent (eg: Highlights)
	if (apply_filters('post_references:skip_sendemail', false, $post_id)) {
		return;
	}

	// Check if the post has references. If so, also send email to the owners of those
	if ($references = GD_MetaManager::get_post_meta($post_id, GD_METAKEY_POST_REFERENCES)) {

		$post_name = gd_get_postname($post_id);
		$url = get_permalink($post_id);
		$title = get_the_title($post_id);
		$post_html = gd_sendemail_get_posthtml($post_id);

		// Get the name of the poster
		$post_author_id = get_post_field( 'post_author', $post_id );
		$author_name = get_the_author_meta('display_name', $post_author_id);

		foreach ($references as $reference_post_id) {

			$reference_post_name = gd_get_postname($reference_post_id);
			$reference_url = get_permalink($reference_post_id);
			$reference_title = get_the_title($reference_post_id);

			$reference_subject = sprintf( 
				__( 'A new %s was posted referencing "%s"', 'pop-coreprocessors' ), 
				$post_name, 
				$reference_title 
			);
			$reference_content = sprintf( 
				__( '<p>Your %s <a href="%s">%s</a> has been referenced by a new %s:</p>', 'pop-coreprocessors'), 
				$reference_post_name,
				$reference_url,
				$reference_title,
				$post_name
			);
			$reference_content .= $post_html;

			gd_sendemail_to_users_from_post($reference_post_id, $reference_subject, $reference_content);	
		}
	}
}

add_action('pending_to_publish', 'gd_sendemail_to_users_from_post_postapproved', 10, 1);
function gd_sendemail_to_users_from_post_postapproved( $post ) {

	$post_id = $post->ID;

	$post_name = gd_get_postname($post_id);
	$url = get_permalink($post_id);
	$title = get_the_title($post_id);
	$post_html = gd_sendemail_get_posthtml($post_id);

	$subject = sprintf( __( 'Your %s was approved!', 'pop-coreprocessors' ), $post_name );
	$content = sprintf( 
		__( '<p>Hurray! Your %s was approved!</p>', 'pop-coreprocessors'), 
		$post_name
	);
	$content .= $post_html;

	gd_sendemail_to_users_from_post($post_id, $subject, $content);
}

/**---------------------------------------------------------------------------------------------------------------
 * Send Email when adding comments
 * ---------------------------------------------------------------------------------------------------------------*/
add_action( 'wp_insert_comment', 'gd_sendemail_to_users_from_comment', 10, 2 );
function gd_sendemail_to_users_from_comment( $comment_id, $comment ) {

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
		__( '<p>There is a response to a comment from <a href="%s">%s</a>:</p>', 'pop-coreprocessors') :
		__( '<p>A new comment has been added to <a href="%s">%s</a>:</p>', 'pop-coreprocessors');

	$content = sprintf( 
		$intro,
		$url,
		$title
	);
	
	$content .= gd_sendemail_get_commenthtml($comment);
	$content .= '<br/>';
	if ($parent) {
		
		$content .= sprintf(
			'<em>%s</em>',
			__('In response to:', 'pop-coreprocessors')
		);
		$content .= gd_sendemail_get_commenthtml($parent);
		$content .= '<br/>';
	}

	$email_template = gd_email_template_folder();
	if ($email_template) {

		$btn_title = __( 'Click here to reply the comment', 'pop-coreprocessors');

		ob_start();
		include ($email_template . GD_EMAIL_TEMPLATE_BUTTON);
		$button = ob_get_clean();
		$content .= str_replace(array('{{TITLE}}', '{{URL}}'), array($btn_title, $url), $button);
	}

	// Possibly the title has html entities, these must be transformed again for the subjects below
	$title = html_entity_decode($title);

	// If this comment is a response, notify the original comment's author
	// Unless they are the same person
	if ($parent) {

		if ($parent->user_id != $comment->user_id) {
			$subject = sprintf( 
				__( '%s replied your comment in “%s”', 'pop-coreprocessors' ), 
				$comment->comment_author, 
				$title 
			);
			gd_sendemail_to_users(array($parent->comment_author_email), array($parent->comment_author), $subject, $content);
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
		__( 'New comment added in “%s”', 'pop-coreprocessors' ), 
		$title
	);
	gd_sendemail_to_users_from_post($post_ids, $subject, $content, $exclude_authors);
}

/**---------------------------------------------------------------------------------------------------------------
 * Send Email when tagging a user in a post or comment
 * ---------------------------------------------------------------------------------------------------------------*/
function gd_sendemail_to_taggedusers($taggedusers_ids, $subject, $content) {

	$emails = array();
	$names = array();
	foreach ($taggedusers_ids as $taggeduser_id) {
		
		$emails[] = get_the_author_meta( 'user_email', $taggeduser_id );
		$names[] = get_the_author_meta( 'display_name', $taggeduser_id );
	}

	gd_sendemail_to_users($emails, $names, $subject, $content, true);
}

add_action('PoP_Mentions:post_tags:tagged_users', 'gd_sendemail_to_users_tagged_in_post', 10, 3);
function gd_sendemail_to_users_tagged_in_post($post_id, $taggedusers_ids, $newly_taggedusers_ids) {

	$post = get_post($post_id);

	// Only for published posts
	if ($post->post_status != 'publish') {
		return;
	}

	$post_name = gd_get_postname($post_id, 'lc');

	$content = sprintf( 
		__( '<p><a href="%s">%s</a> mentioned you in %s:</p>', 'pop-coreprocessors'),
		get_author_posts_url($post->post_author),
		get_the_author_meta('display_name', $post->post_author),
		$post_name
	);

	$content .= gd_sendemail_get_posthtml($post_id);
	
	// Possibly the title has html entities, these must be transformed again for the subjects below
	$title = get_the_title($post_id);
	$title = html_entity_decode($title);

	$subject = sprintf( 
		__( 'You were mentioned in %1$s “%2$s”', 'pop-coreprocessors' ), 
		$post_name,
		$title
	);

	gd_sendemail_to_taggedusers($newly_taggedusers_ids, $subject, $content);
}

add_action('PoP_Mentions:comment_tags:tagged_users', 'gd_sendemail_to_users_tagged_in_comment', 10, 2);
function gd_sendemail_to_users_tagged_in_comment($comment_id, $taggedusers_ids) {

	$comment = get_comment($comment_id);

	// Only for published comments
	if ($comment->comment_approved != "1") {
		return;
	}

	$title = get_the_title($comment->comment_post_ID);
	$url = get_permalink($comment->comment_post_ID);
	$post_name = gd_get_postname($comment->comment_post_ID, 'lc');

	$content = sprintf( 
		__( '<p><a href="%1$s">%2$s</a> mentioned you in a comment from %3$s <a href="%4%s">%5$s</a>:</p>', 'pop-coreprocessors'),
		get_author_posts_url($comment->user_id),
		get_the_author_meta('display_name', $comment->user_id),
		$post_name,
		$url,
		$title
	);

	$content .= gd_sendemail_get_commenthtml($comment);

	if ($comment->comment_parent) {

		$parent = get_comment($comment->comment_parent);
		
		$content .= '<br/>';
		$content .= sprintf(
			'<em>%s</em>',
			__('In response to:', 'pop-coreprocessors')
		);
		$content .= gd_sendemail_get_commenthtml($parent);
	}

	if ($email_template = gd_email_template_folder()) {

		$btn_title = __( 'Click here to reply the comment', 'pop-coreprocessors');

		ob_start();
		include ($email_template . GD_EMAIL_TEMPLATE_BUTTON);
		$button = ob_get_clean();
		$content .= '<br/>';
		$content .= str_replace(array('{{TITLE}}', '{{URL}}'), array($btn_title, $url), $button);
	}
	
	// Possibly the title has html entities, these must be transformed again for the subjects below
	$title = html_entity_decode($title);

	$subject = sprintf( 
		__( 'You were mentioned in a comment from %1$s “%2$s”', 'pop-coreprocessors' ), 
		$post_name,
		$title
	);

	gd_sendemail_to_taggedusers($taggedusers_ids, $subject, $content);
}

/**---------------------------------------------------------------------------------------------------------------
 * Hooks for all account-related stuff: login/logout/forgot pwd
 * ---------------------------------------------------------------------------------------------------------------*/
add_action('retrieve_password_key', 'gd_email_retrieve_password_key');
function gd_email_retrieve_password_key() {

	add_filter('wp_mail_content_type','set_html_content_type');
}
add_filter("retrieve_password_title", 'gd_email_retrieve_password_title');
function gd_email_retrieve_password_title($title) {

	return __('Password reset code', 'pop-coreprocessors');
}
add_filter("retrieve_password_message", 'gd_email_retrievepasswordmessage', 999999, 4);
function gd_email_retrievepasswordmessage($message, $key, $user_login, $user_data) {

	return gd_email_addframe(__('Retrieve password', 'pop-coreprocessors'), $message, $user_data->display_name);
}
add_action('gd_lostpasswordreset', 'gd_email_lostpasswordreset', 10, 1);
function gd_email_lostpasswordreset($user_id) {

	$subject = __('Password reset successful', 'pop-coreprocessors');
	$msg = sprintf(
		'<p>%s %s</p>',
		__('Your password has been changed successfully.', 'pop-coreprocessors'),
		sprintf(
			__('Please <a href="%s">click here to log-in</a>.', 'pop-coreprocessors'),
			wp_login_url()
		)
	);

	gd_sendemail_to_user($user_id, $subject, $msg);
}

// Do not send an email when the user changes the password
add_filter('send_password_change_email', 'gd_email_donotsend', 100000, 1);
add_filter('send_email_change_email', 'gd_email_donotsend', 100000, 1);
function gd_email_donotsend($send) {

	// Returning in such a weird fashion, because on file wp-includes/user.php from WP 4.3.1 it validates like this:
	// if ( ! empty( $send_email_change_email ) ) {
	return array();
}


/**---------------------------------------------------------------------------------------------------------------
 * User's network notification emails: post created
 * ---------------------------------------------------------------------------------------------------------------*/
// Send an email to all post owners's network when a post is published
add_action('gd_createupdate_post:update', 'gd_sendemail_to_usersnetwork_from_post_update', 10, 3);
function gd_sendemail_to_usersnetwork_from_post_update($post_id, $atts, $log) {

	$old_status = $log['previous-status'];

	// Send email if the updated post has been published
	if (get_post_status($post_id) == 'publish' && $old_status != 'publish') {
		gd_sendemail_to_usersnetwork_from_post($post_id);
	}
}
add_action('gd_createupdate_post:create', 'gd_sendemail_to_usersnetwork_from_post_create', 10, 1);
function gd_sendemail_to_usersnetwork_from_post_create($post_id) {

	// Send email if the created post has been published
	if (get_post_status($post_id) == 'publish') {
		gd_sendemail_to_usersnetwork_from_post($post_id);
	}
}
function gd_sendemail_to_usersnetwork_from_post($post_id) {

	// Check if for a given type of post the email must not be sent (eg: Highlights)
	if (gd_sendemail_skip($post_id)) {
		return;
	}

	// No need to check if the post_status is "published", since it's been checked in the previous 2 functions (create/update)
	$post_html = gd_sendemail_get_posthtml($post_id);
	$footer = sprintf(
		'<p><small>%s</small></p>',
		__('You are receiving this notification for belonging to this author’s network. Soon you will be able to configure your notification email’s preferences.', 'pop-coreprocessors')
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
			$post_name = gd_get_postname($post_id);
			$post_title = get_the_title($post_id);
			$subject = sprintf(
				__('%s has created a new %s: “%s”', 'pop-coreprocessors'), 
				$author_name,
				$post_name,
				$post_title
			);
			$content = sprintf( 
				'<p>%s</p>', 
				sprintf(
					__('<b><a href="%s">%s</a></b> has created a new %s:', 'pop-coreprocessors'), 
					$author_url,
					$author_name,
					$post_name
				)
			);
			$content .= $post_html;
			$content .= $footer;

			gd_sendemail_to_users($emails, $names, $subject, $content, true);
		}			
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Follow user
 * ---------------------------------------------------------------------------------------------------------------*/
add_action('gd_followuser', 'gd_email_followuser', 10, 1);
function gd_email_followuser($target_id) {

	$user_id = get_current_user_id();
	$user_html = gd_sendemail_get_userhtml($user_id);
	
	$target_url = get_author_posts_url($target_id);
	$target_name = get_the_author_meta( 'display_name', $target_id);
	$subject = sprintf(__( 'You have a new follower!', 'pop-coreprocessors'), $target_name);
	
	$content = sprintf(
		__( '<p>Congratulations! <a href="%s">You have a new follower</a>:</p>', 'pop-coreprocessors'),
		GD_TemplateManager_Utils::add_tab($target_url, POP_COREPROCESSORS_PAGE_FOLLOWERS)
	);
	$content .= $user_html;

	$content .= '<br/>';
	$content .= __('<p>This user will receive notifications following your activity, such as recommending content, posting a new discussion or comment, and others.</p>', 'pop-coreprocessors');

	$email = get_the_author_meta('user_email', $target_id);	
	gd_sendemail_to_users($email, $target_name, $subject, $content);
}

/**---------------------------------------------------------------------------------------------------------------
 * Recommend post
 * ---------------------------------------------------------------------------------------------------------------*/
add_action('gd_recommendpost', 'gd_email_recommendpost', 10, 1);
function gd_email_recommendpost($post_id) {

	$user_id = get_current_user_id();
	$user_html = gd_sendemail_get_userhtml($user_id);

	$post_name = gd_get_postname($post_id);
	$subject = sprintf(__( 'Your %s was recommended!'), $post_name);
	$content = sprintf( 
		__( '<p>Your %1$s <a href="%2$s">%3$s</a> was recommended by:</p>', 'pop-coreprocessors'), 
		$post_name,
		get_permalink($post_id),
		get_the_title($post_id)
	);
	$content .= $user_html;

	gd_sendemail_to_users_from_post($post_id, $subject, $content);
}

