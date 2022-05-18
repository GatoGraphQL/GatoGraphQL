<?php

class PoP_Share_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_DESTINATIONEMAIL = 'gf-forminputgroup-field-destinationemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_DESTINATIONEMAIL],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_DESTINATIONEMAIL => [PoP_Share_Module_Processor_TextFormInputs::class, PoP_Share_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_DESTINATIONEMAIL],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



