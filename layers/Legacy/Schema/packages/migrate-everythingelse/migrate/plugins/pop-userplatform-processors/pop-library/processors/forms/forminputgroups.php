<?php

class GD_UserPlatform_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_SETTINGSFORMAT = 'forminputgroup-settingsformat';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_SETTINGSFORMAT],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_SETTINGSFORMAT => [GD_UserPlatform_Module_Processor_SelectFormInputs::class, GD_UserPlatform_Module_Processor_SelectFormInputs::MODULE_FORMINPUT_SETTINGSFORMAT],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }
}



