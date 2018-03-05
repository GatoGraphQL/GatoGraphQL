<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Gravity Forms plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_GenericForms_NewsletterUtils {

	public static function get_email_verificationcode($email) {

		return hash_hmac('sha1', $email, POP_GENERICFORMS_PRIVATEKEYS_NEWSLETTERUNSUBSCRIBE);
	}
}