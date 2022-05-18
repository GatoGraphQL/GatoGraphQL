<?php

class PoP_LocationPosts_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const MODULE_DELEGATORFILTER_AUTHORLOCATIONPOSTS = 'delegatorfilter-authorlocationposts';
    public final const MODULE_DELEGATORFILTER_LOCATIONPOSTS = 'delegatorfilter-locationposts';
    public final const MODULE_DELEGATORFILTER_TAGLOCATIONPOSTS = 'delegatorfilter-taglocationposts';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_AUTHORLOCATIONPOSTS],
            [self::class, self::MODULE_DELEGATORFILTER_LOCATIONPOSTS],
            [self::class, self::MODULE_DELEGATORFILTER_TAGLOCATIONPOSTS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_AUTHORLOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::class, PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORLOCATIONPOSTS],
            self::MODULE_DELEGATORFILTER_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::class, PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_LOCATIONPOSTS],
            self::MODULE_DELEGATORFILTER_TAGLOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::class, PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGLOCATIONPOSTS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



