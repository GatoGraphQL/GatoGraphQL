<?php

class PoP_UserCommunitiesProcessors_Module_Processor_EmailFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const MODULE_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY = 'ure-forminputgroup-emailnotifications-network-joinscommunity';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY],
        );
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        $components = array(
            self::MODULE_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY => [GD_URE_Module_Processor_UserProfileCheckboxFormInputs::class, GD_URE_Module_Processor_UserProfileCheckboxFormInputs::MODULE_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY],
        );

        if ($component = $components[$componentVariation[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($componentVariation);
    }

    public function useModuleConfiguration(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:
                return false;
        }

        return parent::useModuleConfiguration($componentVariation);
    }
}



