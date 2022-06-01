<?php

class GD_ContentCreation_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMCOMPONENTGROUP_FEATUREDIMAGE = 'formcomponentgroup-featuredimage';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENTGROUP_FEATUREDIMAGE,
        );
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $components = array(
            self::COMPONENT_FORMCOMPONENTGROUP_FEATUREDIMAGE => [PoP_Module_Processor_FeaturedImageFormComponents::class, PoP_Module_Processor_FeaturedImageFormComponents::COMPONENT_FORMCOMPONENT_FEATUREDIMAGE],
        );

        if ($component = $components[$component->name] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }
}



