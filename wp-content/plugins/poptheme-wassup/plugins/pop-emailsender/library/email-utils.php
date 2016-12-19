<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email public static functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_EmailSender_Utils {

	public static function get_newsletter_email(){
		
		// By default, use the admin_email, but this can be overriden
		return apply_filters('gd_email_newsletter_email', get_bloginfo('admin_email'));
	}
}