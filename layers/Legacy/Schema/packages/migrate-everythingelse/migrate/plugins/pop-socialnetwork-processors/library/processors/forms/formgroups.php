<?php

class PoP_SocialNetwork_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_MESSAGESUBJECT = 'gf-forminputgroup-field-messagesubject';
    public final const COMPONENT_FORMINPUTGROUP_MESSAGETOUSER = 'gf-forminputgroup-field-messagetouser';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_MESSAGESUBJECT],
            [self::class, self::COMPONENT_FORMINPUTGROUP_MESSAGETOUSER],
        );
    }

    public function getComponentSubcomponent(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_MESSAGESUBJECT => [PoP_SocialNetwork_Module_Processor_TextFormInputs::class, PoP_SocialNetwork_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_MESSAGESUBJECT],
            self::COMPONENT_FORMINPUTGROUP_MESSAGETOUSER => [PoP_SocialNetwork_Module_Processor_TextareaFormInputs::class, PoP_SocialNetwork_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_MESSAGETOUSER],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }
}



