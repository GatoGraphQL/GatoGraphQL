<?php

class PoP_Volunteering_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_PHONE = 'gf-forminputgroup-field-phone';
    public final const COMPONENT_FORMINPUTGROUP_WHYVOLUNTEER = 'gf-forminputgroup-field-whyvolunteer';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUTGROUP_PHONE,
            self::COMPONENT_FORMINPUTGROUP_WHYVOLUNTEER,
        );
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_PHONE => [PoP_Volunteering_Module_Processor_TextFormInputs::class, PoP_Volunteering_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_PHONE],
            self::COMPONENT_FORMINPUTGROUP_WHYVOLUNTEER => [PoP_Volunteering_Module_Processor_TextareaFormInputs::class, PoP_Volunteering_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_WHYVOLUNTEER],
        );

        if ($component = $components[$component->name] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }
}



