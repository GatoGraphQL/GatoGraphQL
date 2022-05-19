<?php

class PoP_Locations_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const COMPONENT_SCROLL_LOCATIONS = 'scroll-locations';
    public final const COMPONENT_SCROLL_STATICIMAGE = 'scroll-staticimage';
    public final const COMPONENT_SCROLL_STATICIMAGE_USERORPOST = 'scroll-staticimage-userorpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_LOCATIONS],
            [self::class, self::COMPONENT_SCROLL_STATICIMAGE],
            [self::class, self::COMPONENT_SCROLL_STATICIMAGE_USERORPOST],
        );
    }


    public function getInnerSubcomponent(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_LOCATIONS => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LOCATIONS],
            self::COMPONENT_SCROLL_STATICIMAGE => [PoP_Module_Processor_MapStaticImages::class, PoP_Module_Processor_MapStaticImages::COMPONENT_MAP_STATICIMAGE],
            self::COMPONENT_SCROLL_STATICIMAGE_USERORPOST => [PoP_Module_Processor_MapStaticImages::class, PoP_Module_Processor_MapStaticImages::COMPONENT_MAP_STATICIMAGE_USERORPOST],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}


