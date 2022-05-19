<?php

class PoP_Module_Processor_NoLabelProfileFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_CUP_DISPLAYEMAIL = 'forminputgroup-cup-displayemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUP_DISPLAYEMAIL],
        );
    }

    public function getComponentSubcomponent(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_CUP_DISPLAYEMAIL => [PoP_Module_Processor_CreateUpdateProfileCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdateProfileCheckboxFormInputs::COMPONENT_FORMINPUT_CUP_DISPLAYEMAIL],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }
}



