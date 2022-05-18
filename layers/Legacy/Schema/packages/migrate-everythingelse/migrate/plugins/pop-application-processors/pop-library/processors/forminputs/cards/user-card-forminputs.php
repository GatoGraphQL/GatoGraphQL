<?php

class PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues extends PoP_Module_Processor_UserTriggerLayoutFormComponentValuesBase
{
    public final const MODULE_FORMCOMPONENT_CARD_USER = 'forminput-user-card';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_CARD_USER],
        );
    }

    public function getTriggerSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_CARD_USER:
                return [PoP_Module_Processor_UserHiddenInputAlertFormComponents::class, PoP_Module_Processor_UserHiddenInputAlertFormComponents::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTUSER];
        }

        return parent::getTriggerSubmodule($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_CARD_USER:
                return 'self';
        }

        return parent::getDbobjectField($component);
    }
}



