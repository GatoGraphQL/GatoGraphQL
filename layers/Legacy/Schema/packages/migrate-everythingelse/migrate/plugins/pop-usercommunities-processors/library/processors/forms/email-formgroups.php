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

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $components = array(
            self::COMPONENT_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY => [GD_URE_Module_Processor_UserProfileCheckboxFormInputs::class, GD_URE_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_URE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY],
        );

        if ($component = $components[$component->name] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }

    public function useModuleConfiguration(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:
                return false;
        }

        return parent::useModuleConfiguration($component);
    }
}



