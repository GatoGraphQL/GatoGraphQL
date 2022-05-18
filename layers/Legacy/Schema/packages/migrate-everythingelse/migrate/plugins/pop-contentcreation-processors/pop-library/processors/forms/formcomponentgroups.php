<?php

class GD_ContentCreation_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_FEATUREDIMAGE = 'formcomponentgroup-featuredimage';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENTGROUP_FEATUREDIMAGE],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMCOMPONENTGROUP_FEATUREDIMAGE => [PoP_Module_Processor_FeaturedImageFormComponents::class, PoP_Module_Processor_FeaturedImageFormComponents::MODULE_FORMCOMPONENT_FEATUREDIMAGE],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }
}



