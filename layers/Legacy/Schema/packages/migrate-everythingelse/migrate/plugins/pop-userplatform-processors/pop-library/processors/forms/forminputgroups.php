<?php

class GD_UserPlatform_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_SETTINGSFORMAT = 'forminputgroup-settingsformat';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_SETTINGSFORMAT],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_SETTINGSFORMAT => [GD_UserPlatform_Module_Processor_SelectFormInputs::class, GD_UserPlatform_Module_Processor_SelectFormInputs::COMPONENT_FORMINPUT_SETTINGSFORMAT],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



