<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdateUserTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const COMPONENT_FORMINPUT_CUU_USERNAME = 'forminput-cuu-username';
    public final const COMPONENT_FORMINPUT_CUU_EMAIL = 'forminput-cuu-email';
    public final const COMPONENT_FORMINPUT_CUU_CURRENTPASSWORD = 'forminput-cuu-currentpassword';
    public final const COMPONENT_FORMINPUT_CUU_PASSWORD = 'forminput-cuu-password';
    public final const COMPONENT_FORMINPUT_CUU_NEWPASSWORD = 'forminput-cuu-newpassword';
    public final const COMPONENT_FORMINPUT_CUU_PASSWORDREPEAT = 'forminput-cuu-passwordrepeat';
    public final const COMPONENT_FORMINPUT_CUU_NEWPASSWORDREPEAT = 'forminput-cuu-newpasswordrepeat';
    public final const COMPONENT_FORMINPUT_CUU_FIRSTNAME = 'forminput-cuu-firstName';
    public final const COMPONENT_FORMINPUT_CUU_USERWEBSITEURL = 'forminput-cuu-userwebsiteurl';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_CUU_USERNAME,
            self::COMPONENT_FORMINPUT_CUU_EMAIL,
            self::COMPONENT_FORMINPUT_CUU_CURRENTPASSWORD,
            self::COMPONENT_FORMINPUT_CUU_PASSWORD,
            self::COMPONENT_FORMINPUT_CUU_NEWPASSWORD,
            self::COMPONENT_FORMINPUT_CUU_PASSWORDREPEAT,
            self::COMPONENT_FORMINPUT_CUU_NEWPASSWORDREPEAT,
            self::COMPONENT_FORMINPUT_CUU_FIRSTNAME,
            self::COMPONENT_FORMINPUT_CUU_USERWEBSITEURL,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUU_USERNAME:
                return TranslationAPIFacade::getInstance()->__('Username', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUU_EMAIL:
                return TranslationAPIFacade::getInstance()->__('Email', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUU_CURRENTPASSWORD:
                return TranslationAPIFacade::getInstance()->__('Current Password', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUU_PASSWORD:
                return TranslationAPIFacade::getInstance()->__('Password', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUU_NEWPASSWORD:
                return TranslationAPIFacade::getInstance()->__('New password', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUU_PASSWORDREPEAT:
                return TranslationAPIFacade::getInstance()->__('Repeat password', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUU_NEWPASSWORDREPEAT:
                return TranslationAPIFacade::getInstance()->__('Repeat new password', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUU_FIRSTNAME:
                return TranslationAPIFacade::getInstance()->__('Name', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUU_USERWEBSITEURL:
                return TranslationAPIFacade::getInstance()->__('Website URL', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUU_USERNAME:
            case self::COMPONENT_FORMINPUT_CUU_EMAIL:
            case self::COMPONENT_FORMINPUT_CUU_PASSWORD:
            case self::COMPONENT_FORMINPUT_CUU_NEWPASSWORD:
            case self::COMPONENT_FORMINPUT_CUU_PASSWORDREPEAT:
            case self::COMPONENT_FORMINPUT_CUU_NEWPASSWORDREPEAT:
            case self::COMPONENT_FORMINPUT_CUU_FIRSTNAME:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    public function getType(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUU_CURRENTPASSWORD:
            case self::COMPONENT_FORMINPUT_CUU_PASSWORD:
            case self::COMPONENT_FORMINPUT_CUU_NEWPASSWORD:
            case self::COMPONENT_FORMINPUT_CUU_PASSWORDREPEAT:
            case self::COMPONENT_FORMINPUT_CUU_NEWPASSWORDREPEAT:
                return 'password';
        }
        
        return parent::getType($component, $props);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUU_USERNAME:
                return 'username';

            case self::COMPONENT_FORMINPUT_CUU_EMAIL:
                return 'email';

            case self::COMPONENT_FORMINPUT_CUU_FIRSTNAME:
                return 'firstName';

            case self::COMPONENT_FORMINPUT_CUU_USERWEBSITEURL:
                return 'websiteURL';
        }
        
        return parent::getDbobjectField($component);
    }

    public function clearInput(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUU_CURRENTPASSWORD:
            case self::COMPONENT_FORMINPUT_CUU_PASSWORD:
            case self::COMPONENT_FORMINPUT_CUU_NEWPASSWORD:
            case self::COMPONENT_FORMINPUT_CUU_PASSWORDREPEAT:
            case self::COMPONENT_FORMINPUT_CUU_NEWPASSWORDREPEAT:
                return true;
        }

        return parent::clearInput($component, $props);
    }
}



