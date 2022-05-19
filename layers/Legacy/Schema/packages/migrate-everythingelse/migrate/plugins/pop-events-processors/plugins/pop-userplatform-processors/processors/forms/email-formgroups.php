<?php

class PoP_Events_Module_Processor_EmailFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS = 'forminputgroup-emaildigests-weeklyupcomingevents';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS],
        );
    }

    public function getComponentSubcomponent(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS => [PoP_Events_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Events_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }

    public function useModuleConfiguration(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS:
                return false;
        }

        return parent::useModuleConfiguration($component);
    }
}



