<?php

class PoP_LocationPostCategoryLayouts_Module_Processor_MultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTICOMPONENT_LOCATIONMAP = 'multicomponent-locationmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTICOMPONENT_LOCATIONMAP],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_LOCATIONMAP:
                $ret[] = [PoP_Module_Processor_MapStaticImages::class, PoP_Module_Processor_MapStaticImages::COMPONENT_MAP_STATICIMAGE_USERORPOST];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_LOCATIONMAP:
                $this->setProp([PoP_Module_Processor_MapStaticImages::class, PoP_Module_Processor_MapStaticImages::COMPONENT_MAP_STATICIMAGE_USERORPOST], $props, 'staticmap-size', '480x150');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



