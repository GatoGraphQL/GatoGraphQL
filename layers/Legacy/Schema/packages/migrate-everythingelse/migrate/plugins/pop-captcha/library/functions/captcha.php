<?php
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * Copied from:
 * Captcha Code Authentication Plugin
 * http://www.vinojcardoza.com/captcha-code-authentication/
 */

class GD_Captcha
{
    public static function getImageSrc($encoded, $random)
    {
        // Encode to add as parameters in the URL
        $encoded = rawurlencode($encoded);
        $random = rawurlencode($random);

        // Allow to override the image src URL, as to set the private key from the website's environment-constants
        return HooksAPIFacade::getInstance()->applyFilters(
            'GD_Captcha:image-src',
            sprintf(POP_CAPTCHA_URL.'/directaccess-library/captcha/captcha.png.php?encoded=%s&random=%s', $encoded, $random),
            $encoded,
            $random
        );
    }

    public static function validate($value)
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        // No captcha?
        $captcha = $value['input'];
        if (empty($captcha)) {
            return new Error(
                'captcha-empty',
                $translationAPI->__('Captcha is empty.', 'pop-coreprocessors')
            );
        }

        // Compare the decoded captcha with the user value
        $encoded = $value['encoded'];
        $random = $value['random'];

        // Check if the encoded string matches the input
        $userinput_encoded = PoP_CaptchaEncodeDecode::encode($captcha, $random);

        if ($userinput_encoded !== $encoded) {
            return new Error(
                'captcha-mismatch',
                $translationAPI->__('Captcha doesn\'t match.', 'pop-coreprocessors')
            );
        }

        return true;
    }
}
