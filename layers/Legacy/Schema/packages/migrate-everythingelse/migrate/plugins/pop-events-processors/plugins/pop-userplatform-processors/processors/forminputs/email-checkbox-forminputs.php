<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Events_Module_Processor_UserProfileCheckboxFormInputs extends PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs
{
    public final const COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS = 'forminput-emaildigests-weeklyupcomingevents';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS:
                return TranslationAPIFacade::getInstance()->__('Upcoming events (weekly)', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getCheckboxValue(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS:
                $values = array(
                    self::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS => POP_USERPREFERENCES_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS,
                );
                return $values[$component->name];
        }

        return parent::getCheckboxValue($component, $props);
    }
}



