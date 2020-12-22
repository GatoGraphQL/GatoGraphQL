<?php

class PoP_UserStance_Module_Processor_PostHiddenInputAlertFormComponents extends PoP_Module_Processor_PostHiddenInputAlertFormComponentsBase
{
    public const MODULE_FORMCOMPONENT_HIDDENINPUTALERT_STANCETARGET = 'formcomponent-hiddeninputalert-stancetarget';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_STANCETARGET],
        );
    }
    
    public function getHiddeninputModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_STANCETARGET:
                return [PoP_UserStance_Processor_HiddenInputFormInputs::class, PoP_UserStance_Processor_HiddenInputFormInputs::MODULE_FORMINPUT_HIDDENINPUT_STANCETARGET];
        }

        return parent::getHiddeninputModule($module);
    }
}



