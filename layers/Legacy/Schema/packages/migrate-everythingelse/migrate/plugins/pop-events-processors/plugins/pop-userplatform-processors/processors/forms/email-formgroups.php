<?php

class PoP_Events_Module_Processor_EmailFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS = 'forminputgroup-emaildigests-weeklyupcomingevents';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS => [PoP_Events_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Events_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }

    public function useModuleConfiguration(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS:
                return false;
        }

        return parent::useModuleConfiguration($module);
    }
}



