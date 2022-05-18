<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_LoginTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_LOGIN_USERNAME = 'forminput-login-username';
    public final const MODULE_FORMINPUT_LOGIN_PWD = 'forminput-login-pwd';
    public final const MODULE_FORMINPUT_LOSTPWD_USERNAME = 'forminput-lostpwd-username';
    public final const MODULE_FORMINPUT_LOSTPWDRESET_CODE = 'forminput-lostpwdreset-code';
    public final const MODULE_FORMINPUT_LOSTPWDRESET_NEWPASSWORD = 'forminput-lostpwdreset-newpassword';
    public final const MODULE_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT = 'forminput-lostpwdreset-passwordrepeat';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_LOGIN_USERNAME],
            [self::class, self::COMPONENT_FORMINPUT_LOGIN_PWD],
            [self::class, self::COMPONENT_FORMINPUT_LOSTPWD_USERNAME],
            [self::class, self::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE],
            [self::class, self::COMPONENT_FORMINPUT_LOSTPWDRESET_NEWPASSWORD],
            [self::class, self::COMPONENT_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_LOGIN_USERNAME:
                return TranslationAPIFacade::getInstance()->__('Username or email', 'pop-coreprocessors');
            
            case self::COMPONENT_FORMINPUT_LOGIN_PWD:
                return TranslationAPIFacade::getInstance()->__('Password', 'pop-coreprocessors');
            
            case self::COMPONENT_FORMINPUT_LOSTPWD_USERNAME:
                return TranslationAPIFacade::getInstance()->__('Username or email', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE:
                return TranslationAPIFacade::getInstance()->__('Code', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_LOSTPWDRESET_NEWPASSWORD:
                return TranslationAPIFacade::getInstance()->__('New password', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT:
                return TranslationAPIFacade::getInstance()->__('Repeat password', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE:
            case self::COMPONENT_FORMINPUT_LOSTPWDRESET_NEWPASSWORD:
            case self::COMPONENT_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    // function getName(array $component) {

    //     switch ($component[1]) {

    //         case self::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE:
                
    //             return 'code';
    //     }
        
    //     return parent::getName($component);
    // }

    public function getType(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_LOGIN_PWD:
            case self::COMPONENT_FORMINPUT_LOSTPWDRESET_NEWPASSWORD:
            case self::COMPONENT_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT:
                return 'password';
        }
        
        return parent::getType($component, $props);
    }

    public function clearInput(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_LOGIN_USERNAME:
            case self::COMPONENT_FORMINPUT_LOGIN_PWD:
            case self::COMPONENT_FORMINPUT_LOSTPWD_USERNAME:
            case self::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE:
            case self::COMPONENT_FORMINPUT_LOSTPWDRESET_NEWPASSWORD:
            case self::COMPONENT_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT:
                return true;
        }

        return parent::clearInput($component, $props);
    }
}



