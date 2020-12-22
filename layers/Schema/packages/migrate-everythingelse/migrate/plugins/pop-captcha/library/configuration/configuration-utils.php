<?php

class PoP_Captcha_ConfigurationUtils
{
    public static function captchaEnabled()
    {
        return
        defined('POP_CAPTCHA_PRIVATEKEYS_CAPTCHA') && POP_CAPTCHA_PRIVATEKEYS_CAPTCHA &&
        defined('POP_CAPTCHA_PRIVATEKEYS_IV') && POP_CAPTCHA_PRIVATEKEYS_IV;
    }
}
