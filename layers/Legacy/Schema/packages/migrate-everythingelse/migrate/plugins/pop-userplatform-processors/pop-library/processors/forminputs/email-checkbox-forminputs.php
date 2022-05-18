<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UserProfileCheckboxFormInputs extends PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs
{
    public final const MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST = 'forminput-emailnotifications-general-newpost';
    public final const MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS = 'forminput-emaildigests-weeklylatestposts';
    public final const MODULE_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS = 'forminput-emaildigests-specialposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST],
            [self::class, self::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS],
            [self::class, self::MODULE_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST:
                return TranslationAPIFacade::getInstance()->__('New content is posted on the website', 'pop-coreprocessors');
            
            case self::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS:
                return TranslationAPIFacade::getInstance()->__('New content by the community (weekly)', 'pop-coreprocessors');
            
            case self::MODULE_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS:
                return TranslationAPIFacade::getInstance()->__('Special posts or announcements', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getCheckboxValue(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST:
            case self::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS:
            case self::MODULE_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS:
                $values = array(
                    self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST => POP_USERPREFERENCES_EMAILNOTIFICATIONS_GENERAL_NEWPOST,
                    self::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS => POP_USERPREFERENCES_EMAILDIGESTS_WEEKLYLATESTPOSTS,
                    self::MODULE_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS => POP_USERPREFERENCES_EMAILDIGESTS_SPECIALPOSTS,
                );
                return $values[$component[1]];
        }

        return parent::getCheckboxValue($component, $props);
    }
}



