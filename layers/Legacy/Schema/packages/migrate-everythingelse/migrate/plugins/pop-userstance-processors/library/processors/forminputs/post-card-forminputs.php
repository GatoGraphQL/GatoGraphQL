<?php

class PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues extends PoP_Module_Processor_PostTriggerLayoutFormComponentValuesBase
{
    public final const COMPONENT_FORMCOMPONENT_CARD_STANCETARGET = 'formcomponent-card-stancetarget';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_CARD_STANCETARGET],
        );
    }

    public function getTriggerSubcomponent(\PoP\ComponentModel\Component\Component $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_CARD_STANCETARGET:
                return [PoP_UserStance_Module_Processor_PostHiddenInputAlertFormComponents::class, PoP_UserStance_Module_Processor_PostHiddenInputAlertFormComponents::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_STANCETARGET];
        }

        return parent::getTriggerSubcomponent($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_CARD_STANCETARGET:
                return 'stancetarget';
        }

        return parent::getDbobjectField($component);
    }
}



