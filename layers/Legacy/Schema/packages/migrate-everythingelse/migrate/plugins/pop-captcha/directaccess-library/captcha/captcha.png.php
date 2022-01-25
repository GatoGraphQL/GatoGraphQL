<?php

/**
 * Copied from:
 * Captcha Code Authentication Plugin
 * http://www.vinojcardoza.com/captcha-code-authentication/
 */

require 'captcha-settings.php';

// Load the private key
require_once dirname(dirname(dirname(__FILE__))).'/config/constants.php';

// Load PoP_CaptchaEncodeDecode
require_once dirname(dirname(dirname(__FILE__))).'/library/captcha-utils/load.php';

// Validate that the private key has been defined, or fail otherwise
require_once 'validation.php';

// If it reaches here, there are not issues. Proceed to generate the captcha
$encoded = $_GET['encoded'];
$random = $_GET['random'];

if ($encoded && $random) {
    // Since these parameters were encoded, they must be decoded
    $encoded = rawurldecode($encoded);
    $random = rawurldecode($random);

    $captchacode = PoP_CaptchaEncodeDecode::decode($encoded, $random);
}
// We don't care if there is no value since that's cheating, the validation will then always fail
// But still, generate some captcha or it will be an error
else {
    $captchacode = '0000';
}

require 'generate-captcha.php';
