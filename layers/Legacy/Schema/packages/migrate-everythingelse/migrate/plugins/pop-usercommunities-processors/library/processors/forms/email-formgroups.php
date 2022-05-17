<?php

class PoP_UserCommunitiesProcessors_Module_Processor_EmailFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const MODULE_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY = 'ure-forminputgroup-emailnotifications-network-joinscommunity';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY => [GD_URE_Module_Processor_UserProfileCheckboxFormInputs::class, GD_URE_Module_Processor_UserProfileCheckboxFormInputs::MODULE_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }

    public function useModuleConfiguration(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:
                return false;
        }

        return parent::useModuleConfiguration($module);
    }
}



