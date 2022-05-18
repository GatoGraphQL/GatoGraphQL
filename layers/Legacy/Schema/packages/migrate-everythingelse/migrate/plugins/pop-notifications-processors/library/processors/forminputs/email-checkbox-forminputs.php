<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Notifications_Module_Processor_UserProfileCheckboxFormInputs extends PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs
{
    public final const COMPONENT_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS = 'forminput-emaildigests-dailynotifications';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS:
                return TranslationAPIFacade::getInstance()->__('My notifications (daily)', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getCheckboxValue(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS:
                $values = array(
                    self::COMPONENT_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS => POP_USERPREFERENCES_EMAILDIGESTS_DAILYNOTIFICATIONS,
                );
                return $values[$component[1]];
        }

        return parent::getCheckboxValue($component, $props);
    }
}



