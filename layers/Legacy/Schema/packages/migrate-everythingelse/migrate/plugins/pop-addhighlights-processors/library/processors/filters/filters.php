<?php

class PoP_AddHighlights_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public const MODULE_FILTER_HIGHLIGHTS = 'filter-highlights';
    public const MODULE_FILTER_AUTHORHIGHLIGHTS = 'filter-authorhighlights';
    public const MODULE_FILTER_MYHIGHLIGHTS = 'filter-myhighlights';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_HIGHLIGHTS],
            [self::class, self::MODULE_FILTER_AUTHORHIGHLIGHTS],
            [self::class, self::MODULE_FILTER_MYHIGHLIGHTS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FILTER_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomFilterInners::class, PoP_AddHighlights_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_HIGHLIGHTS],
            self::MODULE_FILTER_AUTHORHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomFilterInners::class, PoP_AddHighlights_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_AUTHORHIGHLIGHTS],
            self::MODULE_FILTER_MYHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomFilterInners::class, PoP_AddHighlights_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_MYHIGHLIGHTS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}


