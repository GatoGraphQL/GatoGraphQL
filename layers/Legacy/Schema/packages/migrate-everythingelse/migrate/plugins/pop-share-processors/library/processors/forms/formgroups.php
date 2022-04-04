<?php

class PoP_Share_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_DESTINATIONEMAIL = 'gf-forminputgroup-field-destinationemail';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_DESTINATIONEMAIL],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_DESTINATIONEMAIL => [PoP_Share_Module_Processor_TextFormInputs::class, PoP_Share_Module_Processor_TextFormInputs::MODULE_FORMINPUT_DESTINATIONEMAIL],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }
}



