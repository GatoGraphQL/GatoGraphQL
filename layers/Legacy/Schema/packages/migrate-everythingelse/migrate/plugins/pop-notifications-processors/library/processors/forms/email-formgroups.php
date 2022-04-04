<?php

class PoP_Notifications_Module_Processor_EmailFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_EMAILDIGESTS_DAILYNOTIFICATIONS = 'forminputgroup-emaildigests-dailynotifications';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_DAILYNOTIFICATIONS],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_DAILYNOTIFICATIONS => [PoP_Notifications_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Notifications_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }

    public function useComponentConfiguration(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_DAILYNOTIFICATIONS:
                return false;
        }

        return parent::useComponentConfiguration($module);
    }
}



