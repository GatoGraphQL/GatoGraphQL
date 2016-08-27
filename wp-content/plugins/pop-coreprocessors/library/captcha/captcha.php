<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Copied from:
 * Captcha Code Authentication Plugin
 * http://www.vinojcardoza.com/captcha-code-authentication/
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Captcha {

	public static function get_image_src($session) {

		$folder = POP_COREPROCESSORS_URI_LIB.'/captcha';
		return sprintf($folder.'/captcha.png.php?rand=%s&session=%s', rand(), $session);
	}

	public static function validate($captcha, $session) {

		$errors = new WP_Error();

		$session_code = GD_CaptchaUtils::get_session_code($session);
		if (!$session_code) {

			$errors->add( 'problem', __('Captcha was not generated properly, please refresh page.', 'pop-coreprocessors'));
			return $errors;
		}

		// No captcha?
		if (empty($captcha)) {

			$errors->add( 'empty', __('Captcha is empty.', 'pop-coreprocessors'));
			return $errors;
		}

		// $captcha = $_REQUEST['captcha_code'];

		// Captcha matches?
		if ($session_code !== $captcha) {

			$errors->add( 'mismatches', __('Captcha doesn\'t match.', 'pop-coreprocessors'));
			return $errors;
		}

		return true;
	}
}