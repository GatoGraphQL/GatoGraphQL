<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Notifications_Module_Processor_UserProfileCheckboxFormInputs extends PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs
{
    public final const COMPONENT_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS = 'forminput-emaildigests-dailynotifications';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS:
                return TranslationAPIFacade::getInstance()->__('My notifications (daily)', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getCheckboxValue(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS:
                $values = array(
                    self::COMPONENT_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS => POP_USERPREFERENCES_EMAILDIGESTS_DAILYNOTIFICATIONS,
                );
                return $values[$component->name];
        }

        return parent::getCheckboxValue($component, $props);
    }
}



