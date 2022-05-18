<?php

class PoP_Events_Module_Processor_EmailFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS = 'forminputgroup-emaildigests-weeklyupcomingevents';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS],
        );
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS => [PoP_Events_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Events_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS],
        );

        if ($component = $components[$componentVariation[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($componentVariation);
    }

    public function useModuleConfiguration(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS:
                return false;
        }

        return parent::useModuleConfiguration($componentVariation);
    }
}



