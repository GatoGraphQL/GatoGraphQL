<?php

class PoP_LocationPostCategoryLayouts_Module_Processor_MultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTICOMPONENT_LOCATIONMAP = 'multicomponent-locationmap';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTICOMPONENT_LOCATIONMAP,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENT_LOCATIONMAP:
                $ret[] = [PoP_Module_Processor_MapStaticImages::class, PoP_Module_Processor_MapStaticImages::COMPONENT_MAP_STATICIMAGE_USERORPOST];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENT_LOCATIONMAP:
                $this->setProp([PoP_Module_Processor_MapStaticImages::class, PoP_Module_Processor_MapStaticImages::COMPONENT_MAP_STATICIMAGE_USERORPOST], $props, 'staticmap-size', '480x150');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



