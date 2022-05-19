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

    public function getComponentSubcomponent(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_DAILYNOTIFICATIONS => [PoP_Notifications_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Notifications_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILDIGESTS_DAILYNOTIFICATIONS],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }

    public function useModuleConfiguration(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_DAILYNOTIFICATIONS:
                return false;
        }

        return parent::useModuleConfiguration($component);
    }
}



