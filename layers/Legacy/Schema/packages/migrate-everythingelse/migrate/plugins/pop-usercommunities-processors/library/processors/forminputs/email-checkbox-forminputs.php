<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_UserProfileCheckboxFormInputs extends PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs
{
    public final const COMPONENT_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY = 'ure-forminput-emailnotifications-network-joinscommunity';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY],
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Joins a community', 'ure-popprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getCheckboxValue(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:
                $values = array(
                    self::COMPONENT_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY => POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY,
                );
                return $values[$component->name];
        }

        return parent::getCheckboxValue($component, $props);
    }
}



