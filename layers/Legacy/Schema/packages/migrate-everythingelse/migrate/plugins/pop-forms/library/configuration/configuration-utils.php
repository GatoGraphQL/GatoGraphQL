<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Forms_ConfigurationUtils
{
    public static function captchaEnabled()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_Forms_ConfigurationUtils:captcha-enabled',
            false
        );
    }
}
