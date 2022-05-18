<?php

class PoP_Module_Processor_EmailFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_GENERAL_NEWPOST = 'forminputgroup-emailnotifications-general-newpost';
    public final const MODULE_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYLATESTPOSTS = 'forminputgroup-emaildigests-weeklylatestposts';
    public final const MODULE_FORMINPUTGROUP_EMAILDIGESTS_SPECIALPOSTS = 'forminputgroup-emaildigests-specialposts';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_GENERAL_NEWPOST],
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYLATESTPOSTS],
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_SPECIALPOSTS],
        );
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_GENERAL_NEWPOST => [PoP_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST],
            self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYLATESTPOSTS => [PoP_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS],
            self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_SPECIALPOSTS => [PoP_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS],
        );

        if ($component = $components[$componentVariation[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($componentVariation);
    }

    public function useModuleConfiguration(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_GENERAL_NEWPOST:
            case self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYLATESTPOSTS:
            case self::MODULE_FORMINPUTGROUP_EMAILDIGESTS_SPECIALPOSTS:
                return false;
        }

        return parent::useModuleConfiguration($componentVariation);
    }
}



