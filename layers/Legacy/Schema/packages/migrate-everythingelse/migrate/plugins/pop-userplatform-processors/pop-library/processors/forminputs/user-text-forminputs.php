<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdateUserTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public const MODULE_FORMINPUT_CUU_USERNAME = 'forminput-cuu-username';
    public const MODULE_FORMINPUT_CUU_EMAIL = 'forminput-cuu-email';
    public const MODULE_FORMINPUT_CUU_CURRENTPASSWORD = 'forminput-cuu-currentpassword';
    public const MODULE_FORMINPUT_CUU_PASSWORD = 'forminput-cuu-password';
    public const MODULE_FORMINPUT_CUU_NEWPASSWORD = 'forminput-cuu-newpassword';
    public const MODULE_FORMINPUT_CUU_PASSWORDREPEAT = 'forminput-cuu-passwordrepeat';
    public const MODULE_FORMINPUT_CUU_NEWPASSWORDREPEAT = 'forminput-cuu-newpasswordrepeat';
    public const MODULE_FORMINPUT_CUU_FIRSTNAME = 'forminput-cuu-firstname';
    public const MODULE_FORMINPUT_CUU_USERWEBSITEURL = 'forminput-cuu-userwebsiteurl';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CUU_USERNAME],
            [self::class, self::MODULE_FORMINPUT_CUU_EMAIL],
            [self::class, self::MODULE_FORMINPUT_CUU_CURRENTPASSWORD],
            [self::class, self::MODULE_FORMINPUT_CUU_PASSWORD],
            [self::class, self::MODULE_FORMINPUT_CUU_NEWPASSWORD],
            [self::class, self::MODULE_FORMINPUT_CUU_PASSWORDREPEAT],
            [self::class, self::MODULE_FORMINPUT_CUU_NEWPASSWORDREPEAT],
            [self::class, self::MODULE_FORMINPUT_CUU_FIRSTNAME],
            [self::class, self::MODULE_FORMINPUT_CUU_USERWEBSITEURL],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUU_USERNAME:
                return TranslationAPIFacade::getInstance()->__('Username', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUU_EMAIL:
                return TranslationAPIFacade::getInstance()->__('Email', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUU_CURRENTPASSWORD:
                return TranslationAPIFacade::getInstance()->__('Current Password', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUU_PASSWORD:
                return TranslationAPIFacade::getInstance()->__('Password', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUU_NEWPASSWORD:
                return TranslationAPIFacade::getInstance()->__('New password', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUU_PASSWORDREPEAT:
                return TranslationAPIFacade::getInstance()->__('Repeat password', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUU_NEWPASSWORDREPEAT:
                return TranslationAPIFacade::getInstance()->__('Repeat new password', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUU_FIRSTNAME:
                return TranslationAPIFacade::getInstance()->__('Name', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUU_USERWEBSITEURL:
                return TranslationAPIFacade::getInstance()->__('Website URL', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUU_USERNAME:
            case self::MODULE_FORMINPUT_CUU_EMAIL:
            case self::MODULE_FORMINPUT_CUU_PASSWORD:
            case self::MODULE_FORMINPUT_CUU_NEWPASSWORD:
            case self::MODULE_FORMINPUT_CUU_PASSWORDREPEAT:
            case self::MODULE_FORMINPUT_CUU_NEWPASSWORDREPEAT:
            case self::MODULE_FORMINPUT_CUU_FIRSTNAME:
                return true;
        }
        
        return parent::isMandatory($module, $props);
    }

    public function getType(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUU_CURRENTPASSWORD:
            case self::MODULE_FORMINPUT_CUU_PASSWORD:
            case self::MODULE_FORMINPUT_CUU_NEWPASSWORD:
            case self::MODULE_FORMINPUT_CUU_PASSWORDREPEAT:
            case self::MODULE_FORMINPUT_CUU_NEWPASSWORDREPEAT:
                return 'password';
        }
        
        return parent::getType($module, $props);
    }

    public function getDbobjectField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUU_USERNAME:
                return 'username';

            case self::MODULE_FORMINPUT_CUU_EMAIL:
                return 'email';

            case self::MODULE_FORMINPUT_CUU_FIRSTNAME:
                return 'firstname';

            case self::MODULE_FORMINPUT_CUU_USERWEBSITEURL:
                return 'websiteURL';
        }
        
        return parent::getDbobjectField($module);
    }

    public function clearInput(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUU_CURRENTPASSWORD:
            case self::MODULE_FORMINPUT_CUU_PASSWORD:
            case self::MODULE_FORMINPUT_CUU_NEWPASSWORD:
            case self::MODULE_FORMINPUT_CUU_PASSWORDREPEAT:
            case self::MODULE_FORMINPUT_CUU_NEWPASSWORDREPEAT:
                return true;
        }

        return parent::clearInput($module, $props);
    }
}



