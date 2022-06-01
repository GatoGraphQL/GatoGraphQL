<?php

class PoP_ContentCreation_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_WHYFLAG = 'gf-forminputgroup-field-whyflag';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_WHYFLAG],
        );
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_WHYFLAG => [PoP_ContentCreation_Module_Processor_TextareaFormInputs::class, PoP_ContentCreation_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_WHYFLAG],
        );

        if ($component = $components[$component->name] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }
}



