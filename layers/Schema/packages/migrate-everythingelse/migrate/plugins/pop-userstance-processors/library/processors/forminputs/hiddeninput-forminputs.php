<?php

class PoP_UserStance_Processor_HiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public const MODULE_FORMINPUT_HIDDENINPUT_STANCETARGET = 'forminput-hiddeninput-stancetarget';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_STANCETARGET],
        );
    }

    public function getName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_HIDDENINPUT_STANCETARGET:
                return POP_INPUTNAME_STANCETARGET;
        }

        return parent::getName($module);
    }
}
