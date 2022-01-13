<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Forms_ConfigurationUtils
{
    public static function captchaEnabled()
    {
        return \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_Forms_ConfigurationUtils:captcha-enabled',
            false
        );
    }
}
