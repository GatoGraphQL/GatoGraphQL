<?php

class GD_EM_Module_Processor_CreateLocationForms extends PoP_Module_Processor_FormsBase
{
    public const MODULE_FORM_CREATELOCATION = 'em-form-createlocation';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_CREATELOCATION],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORM_CREATELOCATION:
                return [GD_EM_Module_Processor_CreateLocationFormInners::class, GD_EM_Module_Processor_CreateLocationFormInners::MODULE_FORMINNER_CREATELOCATION];
        }

        return parent::getInnerSubmodule($module);
    }
}



