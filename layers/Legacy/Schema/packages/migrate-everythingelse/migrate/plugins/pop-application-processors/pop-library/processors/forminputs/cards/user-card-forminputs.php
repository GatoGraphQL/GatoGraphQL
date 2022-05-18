<?php

class PoP_Application_Module_Processor_UserTriggerLayoutFormComponentValues extends PoP_Module_Processor_UserTriggerLayoutFormComponentValuesBase
{
    public final const MODULE_FORMCOMPONENT_CARD_USER = 'forminput-user-card';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_CARD_USER],
        );
    }

    public function getTriggerSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_CARD_USER:
                return [PoP_Module_Processor_UserHiddenInputAlertFormComponents::class, PoP_Module_Processor_UserHiddenInputAlertFormComponents::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTUSER];
        }

        return parent::getTriggerSubmodule($module);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_CARD_USER:
                return 'self';
        }

        return parent::getDbobjectField($module);
    }
}



