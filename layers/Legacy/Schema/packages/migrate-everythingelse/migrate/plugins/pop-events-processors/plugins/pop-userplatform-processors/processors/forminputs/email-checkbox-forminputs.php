<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Events_Module_Processor_UserProfileCheckboxFormInputs extends PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs
{
    public final const COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS = 'forminput-emaildigests-weeklyupcomingevents';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS:
                return TranslationAPIFacade::getInstance()->__('Upcoming events (weekly)', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getCheckboxValue(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS:
                $values = array(
                    self::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS => POP_USERPREFERENCES_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS,
                );
                return $values[$component[1]];
        }

        return parent::getCheckboxValue($component, $props);
    }
}



