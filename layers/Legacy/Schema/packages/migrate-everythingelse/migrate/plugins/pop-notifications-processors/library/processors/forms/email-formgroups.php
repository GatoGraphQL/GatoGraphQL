<?php

class PoP_Notifications_Module_Processor_EmailFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_DAILYNOTIFICATIONS = 'forminputgroup-emaildigests-dailynotifications';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_DAILYNOTIFICATIONS],
        );
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_DAILYNOTIFICATIONS => [PoP_Notifications_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Notifications_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS],
        );

        if ($component = $components[$component->name] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }

    public function useModuleConfiguration(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_DAILYNOTIFICATIONS:
                return false;
        }

        return parent::useModuleConfiguration($component);
    }
}



