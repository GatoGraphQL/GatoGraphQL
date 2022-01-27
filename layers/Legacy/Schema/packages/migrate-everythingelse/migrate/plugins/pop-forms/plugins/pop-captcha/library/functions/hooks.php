<?php

class PoP_Forms_Captcha_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Forms_ConfigurationUtils:captcha-enabled',
            array(PoP_Captcha_ConfigurationUtils::class, 'captchaEnabled')
        );
    }
}

/**
 * Initialization
 */
new PoP_Forms_Captcha_Hooks();
