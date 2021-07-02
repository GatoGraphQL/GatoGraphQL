<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_Module_Processor_UserProfileCheckboxFormInputs extends PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs
{
    public const MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST = 'forminput-emailnotifications-general-newpost';
    public const MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS = 'forminput-emaildigests-weeklylatestposts';
    public const MODULE_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS = 'forminput-emaildigests-specialposts';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST],
            [self::class, self::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS],
            [self::class, self::MODULE_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST:
                return TranslationAPIFacade::getInstance()->__('New content is posted on the website', 'pop-coreprocessors');
            
            case self::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS:
                return TranslationAPIFacade::getInstance()->__('New content by the community (weekly)', 'pop-coreprocessors');
            
            case self::MODULE_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS:
                return TranslationAPIFacade::getInstance()->__('Special posts or announcements', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getCheckboxValue(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST:
            case self::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS:
            case self::MODULE_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS:
                $values = array(
                    self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST => POP_USERPREFERENCES_EMAILNOTIFICATIONS_GENERAL_NEWPOST,
                    self::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS => POP_USERPREFERENCES_EMAILDIGESTS_WEEKLYLATESTPOSTS,
                    self::MODULE_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS => POP_USERPREFERENCES_EMAILDIGESTS_SPECIALPOSTS,
                );
                return $values[$module[1]];
        }

        return parent::getCheckboxValue($module, $props);
    }
}



