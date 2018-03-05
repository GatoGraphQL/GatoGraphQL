<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email public static functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_EmailSender_Utils {

	protected static $headers;

	public static function send_email($to, $subject, $msg, $headers_name = null) {

		if (is_null(self::$headers)) {
			self::init();
		}

		// Header is default, or a custom one
		$headers_name = $headers_name ?? 'default';
		$headers = self::$headers[$headers_name] ?? self::$headers['default'];
		
		wp_mail($to, $subject, $msg, $headers);
	}
	protected static function init() {
		
		// Allow to add extra headers. Eg: newsletters
		self::$headers = apply_filters(
			'PoP_EmailSender_Utils:init:headers',
			array(
				'default' => sprintf(
					"From: %s <%s>\r\n", 
					self::get_from_name(), 
					self::get_from_email()
				).sprintf(
					"Content-Type: %s; charset=\"%s\"\n",
					self::get_contenttype(),
					self::get_charset()
				)
			)
		);
	}

	public static function sendemail_skip($post_id) {

		// Check if for a given type of post the email must not be sent (eg: Highlights)
		return apply_filters('create_post:skip_sendemail', false, $post_id);
	}
	public static function get_admin_notifications_email(){
		
		// By default, use the admin_email, but this can be overriden
		return apply_filters('gd_email_notifications_email', get_bloginfo('admin_email'));
	}
	public static function get_from_name(){
		
		return apply_filters('gd_email_fromname', get_bloginfo('name'));
	}
	public static function get_from_email(){
		
		// By default, use the admin_email, but this can be overriden
		return apply_filters('gd_email_info_email', get_bloginfo('admin_email'));
	}
	public static function get_contenttype(){
		
		return apply_filters('gd_email_contenttype', 'text/html');
	}
	public static function get_charset(){
		
		return apply_filters('gd_email_charset', strtolower(get_option('blog_charset')));
	}

	public static function sendemail_to_users($emails, $names, $subject, $msg, $individual = true, $header = null, $frame = null) {

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
				$emailmsg = PoP_EmailTemplates_Factory::get_instance($frame)->add_emailframe($subject, $msg, array($to), $name);
				self::send_email($to, $subject, $emailmsg, $header);
			}
		}
		else {

			$to = implode(',', $emails);	
			$emailmsg = PoP_EmailTemplates_Factory::get_instance($frame)->add_emailframe($subject, $msg, $emails, $names);
			self::send_email($to, $subject, $emailmsg, $header);
		}
	}

	public static function sendemail_to_users_from_post($post_ids, $subject, $content, $exclude_authors = array()) {

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
		// Just in case that some posts had the same author, filter them to send them the email just once
		$authors = array_unique($authors);

		// Exclude authors
		$authors = array_diff($authors, $exclude_authors);
		foreach ($authors as $author) {
			
			$emails[] = get_the_author_meta( 'user_email', $author );
			$names[] = get_the_author_meta( 'display_name', $author );
		}

		self::sendemail_to_users($emails, $names, $subject, $content, true);

		return $authors;
	}

	public static function sendemail_to_user($user_id, $subject, $msg) {

		$email = get_the_author_meta( 'user_email', $user_id );
		$name = get_the_author_meta('display_name', $user_id);
		
		self::sendemail_to_users($email, $name, $subject, $msg);
	}
}