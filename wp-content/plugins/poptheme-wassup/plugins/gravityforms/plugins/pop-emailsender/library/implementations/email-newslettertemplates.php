<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EMAIL_FRAME_NEWSLETTER', 'newsletter');

class PoP_EmailSender_Templates_Newsletter extends PoP_EmailSender_Templates_Simple {

	function get_name() {

		return GD_EMAIL_FRAME_NEWSLETTER;
	}

	function get_emailframe_beforefooter(/*$frame, */$title, $emails, $names, $template) {

		// $ret = parent::get_emailframe_footer($title, $emails, $names, $template);
		
		// Generate the URL to unsubscribe the first (which should be the only one!) email
		$email = $emails[0];
		$verificationcode = PoPTheme_Wassup_GF_NewsletterUtils::get_email_verificationcode($email);
		$url = get_permalink(POPTHEME_WASSUP_GF_PAGE_NEWSLETTERUNSUBSCRIPTION);
		$url = add_query_arg(GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL, $email, $url);
		$url = add_query_arg(GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE, $verificationcode, $url);
		
		// Add the link to unsubscribe
		return sprintf(
			'<p><small>%s</small></p>',
			sprintf(
				__('<a href="%s">Unsubscribe</a> from this newsletter.', 'pop-emailsender'),
				$url
			)
		)/*.$ret*/;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_EmailSender_Templates_Newsletter();
