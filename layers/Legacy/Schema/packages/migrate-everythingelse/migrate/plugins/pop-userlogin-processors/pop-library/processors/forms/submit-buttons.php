<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_LoginSubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public const MODULE_SUBMITBUTTON_LOGIN = 'submitbutton-login';
    public const MODULE_SUBMITBUTTON_LOSTPWD = 'submitbutton-lostpwd';
    public const MODULE_SUBMITBUTTON_LOSTPWDRESET = 'submitbutton-lostpwdreset';
    public const MODULE_SUBMITBUTTON_LOGOUT = 'submitbutton-logout';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBMITBUTTON_LOGIN],
            [self::class, self::MODULE_SUBMITBUTTON_LOSTPWD],
            [self::class, self::MODULE_SUBMITBUTTON_LOSTPWDRESET],
            [self::class, self::MODULE_SUBMITBUTTON_LOGOUT],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMITBUTTON_LOGIN:
                return TranslationAPIFacade::getInstance()->__('Log in', 'pop-coreprocessors');

            case self::MODULE_SUBMITBUTTON_LOSTPWD:
                return TranslationAPIFacade::getInstance()->__('Get password reset code', 'pop-coreprocessors');

            case self::MODULE_SUBMITBUTTON_LOSTPWDRESET:
                return TranslationAPIFacade::getInstance()->__('Reset password', 'pop-coreprocessors');

            case self::MODULE_SUBMITBUTTON_LOGOUT:
                return TranslationAPIFacade::getInstance()->__('Yes, please log me out', 'pop-coreprocessors');
        }

        return parent::getLabel($module, $props);
    }

    public function getLoadingText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMITBUTTON_LOGIN:
                return TranslationAPIFacade::getInstance()->__('Logging in...', 'pop-coreprocessors');

            case self::MODULE_SUBMITBUTTON_LOGOUT:
                return TranslationAPIFacade::getInstance()->__('Logging out...', 'pop-coreprocessors');

            case self::MODULE_SUBMITBUTTON_LOSTPWD:
            case self::MODULE_SUBMITBUTTON_LOSTPWDRESET:
                return TranslationAPIFacade::getInstance()->__('Sending...', 'pop-coreprocessors');
        }
        
        return parent::getLoadingText($module, $props);
    }
}


