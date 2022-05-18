<?php

class GD_ContentCreation_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_FEATUREDIMAGE = 'formcomponentgroup-featuredimage';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENTGROUP_FEATUREDIMAGE],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FORMCOMPONENTGROUP_FEATUREDIMAGE => [PoP_Module_Processor_FeaturedImageFormComponents::class, PoP_Module_Processor_FeaturedImageFormComponents::COMPONENT_FORMCOMPONENT_FEATUREDIMAGE],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



