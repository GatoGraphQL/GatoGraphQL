<?php

class PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues extends PoP_Module_Processor_UserTriggerLayoutFormComponentValuesBase
{
    public final const COMPONENT_FORMCOMPONENT_CARD_USER = 'forminput-user-card';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_CARD_USER],
        );
    }

    public function getTriggerSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_CARD_USER:
                return [PoP_Module_Processor_UserHiddenInputAlertFormComponents::class, PoP_Module_Processor_UserHiddenInputAlertFormComponents::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTUSER];
        }

        return parent::getTriggerSubcomponent($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_CARD_USER:
                return 'self';
        }

        return parent::getDbobjectField($component);
    }
}



