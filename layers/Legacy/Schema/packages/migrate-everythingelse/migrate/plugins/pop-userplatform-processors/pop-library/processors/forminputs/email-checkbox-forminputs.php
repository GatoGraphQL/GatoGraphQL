<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UserProfileCheckboxFormInputs extends PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs
{
    public final const COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST = 'forminput-emailnotifications-general-newpost';
    public final const COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS = 'forminput-emaildigests-weeklylatestposts';
    public final const COMPONENT_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS = 'forminput-emaildigests-specialposts';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST,
            self::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS,
            self::COMPONENT_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST:
                return TranslationAPIFacade::getInstance()->__('New content is posted on the website', 'pop-coreprocessors');
            
            case self::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS:
                return TranslationAPIFacade::getInstance()->__('New content by the community (weekly)', 'pop-coreprocessors');
            
            case self::COMPONENT_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS:
                return TranslationAPIFacade::getInstance()->__('Special posts or announcements', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getCheckboxValue(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST:
            case self::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS:
            case self::COMPONENT_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS:
                $values = array(
                    self::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST => POP_USERPREFERENCES_EMAILNOTIFICATIONS_GENERAL_NEWPOST,
                    self::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS => POP_USERPREFERENCES_EMAILDIGESTS_WEEKLYLATESTPOSTS,
                    self::COMPONENT_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS => POP_USERPREFERENCES_EMAILDIGESTS_SPECIALPOSTS,
                );
                return $values[$component->name];
        }

        return parent::getCheckboxValue($component, $props);
    }
}



