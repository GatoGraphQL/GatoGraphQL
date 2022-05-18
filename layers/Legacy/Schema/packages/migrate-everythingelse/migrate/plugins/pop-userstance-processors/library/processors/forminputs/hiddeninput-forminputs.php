<?php

class PoP_UserStance_Processor_HiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const MODULE_FORMINPUT_HIDDENINPUT_STANCETARGET = 'forminput-hiddeninput-stancetarget';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_STANCETARGET],
        );
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_HIDDENINPUT_STANCETARGET:
                return POP_INPUTNAME_STANCETARGET;
        }

        return parent::getName($module);
    }
}
