<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

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
