<?php

class PoP_Module_Processor_NoLabelProfileFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_CUP_DISPLAYEMAIL = 'forminputgroup-cup-displayemail';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_CUP_DISPLAYEMAIL],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_CUP_DISPLAYEMAIL => [PoP_Module_Processor_CreateUpdateProfileCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdateProfileCheckboxFormInputs::MODULE_FORMINPUT_CUP_DISPLAYEMAIL],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }
}



