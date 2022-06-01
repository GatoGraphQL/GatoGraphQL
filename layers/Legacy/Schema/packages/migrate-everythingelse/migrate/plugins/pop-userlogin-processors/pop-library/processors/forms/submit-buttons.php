<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_LoginSubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public final const COMPONENT_SUBMITBUTTON_LOGIN = 'submitbutton-login';
    public final const COMPONENT_SUBMITBUTTON_LOSTPWD = 'submitbutton-lostpwd';
    public final const COMPONENT_SUBMITBUTTON_LOSTPWDRESET = 'submitbutton-lostpwdreset';
    public final const COMPONENT_SUBMITBUTTON_LOGOUT = 'submitbutton-logout';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SUBMITBUTTON_LOGIN],
            [self::class, self::COMPONENT_SUBMITBUTTON_LOSTPWD],
            [self::class, self::COMPONENT_SUBMITBUTTON_LOSTPWDRESET],
            [self::class, self::COMPONENT_SUBMITBUTTON_LOGOUT],
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SUBMITBUTTON_LOGIN:
                return TranslationAPIFacade::getInstance()->__('Log in', 'pop-coreprocessors');

            case self::COMPONENT_SUBMITBUTTON_LOSTPWD:
                return TranslationAPIFacade::getInstance()->__('Get password reset code', 'pop-coreprocessors');

            case self::COMPONENT_SUBMITBUTTON_LOSTPWDRESET:
                return TranslationAPIFacade::getInstance()->__('Reset password', 'pop-coreprocessors');

            case self::COMPONENT_SUBMITBUTTON_LOGOUT:
                return TranslationAPIFacade::getInstance()->__('Yes, please log me out', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }

    public function getLoadingText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SUBMITBUTTON_LOGIN:
                return TranslationAPIFacade::getInstance()->__('Logging in...', 'pop-coreprocessors');

            case self::COMPONENT_SUBMITBUTTON_LOGOUT:
                return TranslationAPIFacade::getInstance()->__('Logging out...', 'pop-coreprocessors');

            case self::COMPONENT_SUBMITBUTTON_LOSTPWD:
            case self::COMPONENT_SUBMITBUTTON_LOSTPWDRESET:
                return TranslationAPIFacade::getInstance()->__('Sending...', 'pop-coreprocessors');
        }
        
        return parent::getLoadingText($component, $props);
    }
}


