<?php

class PoP_UserCommunitiesProcessors_Module_Processor_EmailFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const COMPONENT_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY = 'ure-forminputgroup-emailnotifications-network-joinscommunity';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY => [GD_URE_Module_Processor_UserProfileCheckboxFormInputs::class, GD_URE_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }

    public function useModuleConfiguration(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:
                return false;
        }

        return parent::useModuleConfiguration($component);
    }
}



