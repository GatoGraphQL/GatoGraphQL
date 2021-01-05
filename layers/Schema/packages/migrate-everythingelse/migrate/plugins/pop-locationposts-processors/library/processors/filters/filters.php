<?php

class PoP_LocationPosts_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public const MODULE_FILTER_AUTHORLOCATIONPOSTS = 'filter-authorlocationposts';
    public const MODULE_FILTER_TAGLOCATIONPOSTS = 'filter-taglocationposts';
    public const MODULE_FILTER_LOCATIONPOSTS = 'filter-locationposts';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_LOCATIONPOSTS],
            [self::class, self::MODULE_FILTER_AUTHORLOCATIONPOSTS],
            [self::class, self::MODULE_FILTER_TAGLOCATIONPOSTS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FILTER_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomFilterInners::class, PoP_LocationPosts_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_LOCATIONPOSTS],
            self::MODULE_FILTER_AUTHORLOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomFilterInners::class, PoP_LocationPosts_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_AUTHORLOCATIONPOSTS],
            self::MODULE_FILTER_TAGLOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomFilterInners::class, PoP_LocationPosts_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_TAGLOCATIONPOSTS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



