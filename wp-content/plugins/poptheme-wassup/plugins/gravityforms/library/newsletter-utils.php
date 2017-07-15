<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Gravity Forms plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_GF_NewsletterUtils {

	public static function get_email_verificationcode($email) {

		return hash_hmac('sha1', $email, POPTHEME_WASSUP_GF_PRIVATEKEYS_NEWSLETTERUNSUBSCRIBE);
	}
}