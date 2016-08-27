<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Copied from:
 * Captcha Code Authentication Plugin
 * http://www.vinojcardoza.com/captcha-code-authentication/
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CaptchaUtils {

	public static function get_session_code($session) {

		if ((function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
			session_start();
		}
		return $_SESSION['captcha-'.$session];
	}

	public static function set_session_code($session, $code) {

		if ((function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
			session_start();
		}
		$_SESSION['captcha-'.$session] = $code;
	}
}