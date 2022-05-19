<?php

class PoP_LocationPosts_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const COMPONENT_DELEGATORFILTER_AUTHORLOCATIONPOSTS = 'delegatorfilter-authorlocationposts';
    public final const COMPONENT_DELEGATORFILTER_LOCATIONPOSTS = 'delegatorfilter-locationposts';
    public final const COMPONENT_DELEGATORFILTER_TAGLOCATIONPOSTS = 'delegatorfilter-taglocationposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DELEGATORFILTER_AUTHORLOCATIONPOSTS],
            [self::class, self::COMPONENT_DELEGATORFILTER_LOCATIONPOSTS],
            [self::class, self::COMPONENT_DELEGATORFILTER_TAGLOCATIONPOSTS],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        $inners = array(
            self::COMPONENT_DELEGATORFILTER_AUTHORLOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::class, PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORLOCATIONPOSTS],
            self::COMPONENT_DELEGATORFILTER_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::class, PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_LOCATIONPOSTS],
            self::COMPONENT_DELEGATORFILTER_TAGLOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::class, PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGLOCATIONPOSTS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



