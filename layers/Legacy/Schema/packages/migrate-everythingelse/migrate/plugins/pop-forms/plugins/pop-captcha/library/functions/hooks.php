<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Forms_Captcha_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Forms_ConfigurationUtils:captcha-enabled',
            array(PoP_Captcha_ConfigurationUtils::class, 'captchaEnabled')
        );
    }
}

/**
 * Initialization
 */
new PoP_Forms_Captcha_Hooks();
