<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Events_Module_Processor_UserProfileCheckboxFormInputs extends PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs
{
    public final const MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS = 'forminput-emaildigests-weeklyupcomingevents';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS:
                return TranslationAPIFacade::getInstance()->__('Upcoming events (weekly)', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function getCheckboxValue(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS:
                $values = array(
                    self::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS => POP_USERPREFERENCES_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS,
                );
                return $values[$componentVariation[1]];
        }

        return parent::getCheckboxValue($componentVariation, $props);
    }
}



