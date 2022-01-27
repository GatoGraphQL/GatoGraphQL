<?php

class PoP_Forms_ConfigurationUtils
{
    public static function captchaEnabled()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_Forms_ConfigurationUtils:captcha-enabled',
            false
        );
    }
}
