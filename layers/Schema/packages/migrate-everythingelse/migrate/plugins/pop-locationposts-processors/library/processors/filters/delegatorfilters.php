<?php

class PoP_LocationPosts_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public const MODULE_DELEGATORFILTER_AUTHORLOCATIONPOSTS = 'delegatorfilter-authorlocationposts';
    public const MODULE_DELEGATORFILTER_LOCATIONPOSTS = 'delegatorfilter-locationposts';
    public const MODULE_DELEGATORFILTER_TAGLOCATIONPOSTS = 'delegatorfilter-taglocationposts';

    public function getModulesToProcess(): array
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
            self::MODULE_DELEGATORFILTER_AUTHORLOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::class, PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHORLOCATIONPOSTS],
            self::MODULE_DELEGATORFILTER_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::class, PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_LOCATIONPOSTS],
            self::MODULE_DELEGATORFILTER_TAGLOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::class, PoP_LocationPosts_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_TAGLOCATIONPOSTS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



