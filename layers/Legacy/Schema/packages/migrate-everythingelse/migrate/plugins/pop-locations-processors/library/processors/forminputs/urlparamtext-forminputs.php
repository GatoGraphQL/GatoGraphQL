<?php

class GD_EM_Module_Processor_UrlParamTextFormInputs extends PoP_Module_Processor_UrlParamTextFormInputsBase
{
    public final const MODULE_FORMINPUT_URLPARAMTEXT_LOCATIONID = 'forminput-urlparam-locationid';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_URLPARAMTEXT_LOCATIONID],
        );
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_URLPARAMTEXT_LOCATIONID:
                return POP_INPUTNAME_LOCATIONID;
        }

        return parent::getName($module);
    }
}



