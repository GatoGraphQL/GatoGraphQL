<?php

class PoP_Module_Processor_EmailFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_GENERAL_NEWPOST = 'forminputgroup-emailnotifications-general-newpost';
    public final const COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYLATESTPOSTS = 'forminputgroup-emaildigests-weeklylatestposts';
    public final const COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_SPECIALPOSTS = 'forminputgroup-emaildigests-specialposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_GENERAL_NEWPOST],
            [self::class, self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYLATESTPOSTS],
            [self::class, self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_SPECIALPOSTS],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_GENERAL_NEWPOST => [PoP_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_GENERAL_NEWPOST],
            self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYLATESTPOSTS => [PoP_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILDIGESTS_WEEKLYLATESTPOSTS],
            self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_SPECIALPOSTS => [PoP_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILDIGESTS_SPECIALPOSTS],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }

    public function useModuleConfiguration(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_GENERAL_NEWPOST:
            case self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYLATESTPOSTS:
            case self::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_SPECIALPOSTS:
                return false;
        }

        return parent::useModuleConfiguration($component);
    }
}



