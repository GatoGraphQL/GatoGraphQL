<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_UserProfileCheckboxFormInputs extends PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs
{
    public final const MODULE_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY = 'ure-forminput-emailnotifications-network-joinscommunity';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Joins a community', 'ure-popprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getCheckboxValue(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:
                $values = array(
                    self::MODULE_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY => POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY,
                );
                return $values[$component[1]];
        }

        return parent::getCheckboxValue($component, $props);
    }
}



