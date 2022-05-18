<?php

class PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues extends PoP_Module_Processor_PostTriggerLayoutFormComponentValuesBase
{
    public final const MODULE_FORMCOMPONENT_CARD_STANCETARGET = 'formcomponent-card-stancetarget';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_CARD_STANCETARGET],
        );
    }

    public function getTriggerSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_CARD_STANCETARGET:
                return [PoP_UserStance_Module_Processor_PostHiddenInputAlertFormComponents::class, PoP_UserStance_Module_Processor_PostHiddenInputAlertFormComponents::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_STANCETARGET];
        }

        return parent::getTriggerSubmodule($module);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_CARD_STANCETARGET:
                return 'stancetarget';
        }

        return parent::getDbobjectField($module);
    }
}



